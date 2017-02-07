<?php

namespace Flash\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Controller\Component\FlashComponent as CakeFlashComponent;
use Cake\Core\Configure;
use Cake\Event\Event;
use Exception;

/**
 * A flash component to enhance flash message support with stackable messages, both
 * persistent and transient.
 *
 * @author Mark Scherer
 * @copyright 2014 Mark Scherer
 * @license MIT
 *
 * @method void success(string $message, array $options = []) Set a message using "success" element
 * @method void error(string $message, array $options = []) Set a message using "error" element
 * @method void warning(string $message, array $options = []) Set a message using "warning" element
 * @method void info(string $message, array $options = []) Set a message using "info" element
 */
class FlashComponent extends CakeFlashComponent {

	/**
	 * @var array
	 */
	protected $_defaultConfigExt = [
		'headerKey' => 'X-Flash', // Set to empty string to deactivate AJAX response
		'limit' => 10 // Max message limit per key - first in, first out
	];

	/**
	 * @param \Cake\Controller\ComponentRegistry $registry A ComponentRegistry for this component
	 * @param array $config Array of config.
	 */
	public function __construct(ComponentRegistry $registry, array $config = []) {
		$this->_defaultConfig += $this->_defaultConfigExt;
		parent::__construct($registry, $config);
	}

	/**
	 * Called after the Controller::beforeRender(), after the view class is loaded, and before the
	 * Controller::render()
	 *
	 * @param \Cake\Event\Event $event
	 * @return \Cake\Network\Response|null|void
	 */
	public function beforeRender(Event $event) {
		if (!$this->request->is('ajax')) {
			return;
		}

		$headerKey = $this->config('headerKey');
		if (!$headerKey) {
			return;
		}

		$ajaxMessages = array_merge_recursive(
			(array)$this->request->session()->consume('Flash'),
			(array)Configure::consume('TransientFlash')
		);

		$array = [];
		foreach ($ajaxMessages as $key => $stack) {
			foreach ($stack as $message) {
				$array[$key][] = [
					'message' => $message['message'],
					'type' => $message['type'],
					'params' => $message['params']
				];
			}
		}

		// The header can be read with JavaScript and the flash messages can be displayed
		$this->response->header($headerKey, json_encode($array));
	}

	/**
	 * Adds a flash message.
	 * Updates "messages" session content (to enable multiple messages of one type).
	 *
	 * @param string $message Message to output.
	 * @param array|string|null $options Options
	 * @return void
	 */
	public function message($message, $options = null) {
		$options = $this->_mergeOptions($options);

		$this->set($message, $options);

		$this->_assertSessionStackSize($options);
	}

	/**
	 * @param string $message
	 * @param array $options
	 *
	 * @return void
	 */
	public function set($message, array $options = []) {
		$options = $this->_mergeOptions($options);
		$options += $this->config();

		if ($message instanceof Exception) {
			if (!isset($options['params']['code'])) {
				$options['params']['code'] = $message->getCode();
			}
			$message = $message->getMessage();
		}

		if (isset($options['escape']) && !isset($options['params']['escape'])) {
			$options['params']['escape'] = $options['escape'];
		}

		list($plugin, $element) = pluginSplit($options['element']);

		if ($plugin) {
			$options['element'] = $plugin . '.Flash/' . $element;
		} else {
			$options['element'] = 'Flash/' . $element;
		}

		$messages = [];
		if ($options['clear'] === false) {
			$messages = (array)$this->_session->read('Flash.' . $options['key']);
		}

		$messages[] = [
			'type' => $options['type'],
			'message' => $message,
			'key' => $options['key'],
			'element' => $options['element'],
			'params' => $options['params']
		];

		$this->_session->write('Flash.' . $options['key'], $messages);
	}

	/**
	 * @param array|string|null $options
	 *
	 * @return array
	 */
	protected function _mergeOptions($options) {
		if (!is_array($options)) {
			$type = $options;
			if (!$type) {
				$type = 'info';
			}
			$options = [
				'type' => $type,
			];
		}
		if (isset($options['element']) && !isset($options['type'])) {
			$options['type'] = $options['element'];
		}

		$options += ['type' => 'info'];
		$options += ['element' => $options['type']];

		$options += $this->config();

		return $options;
	}

	/**
	 * @param array $options
	 *
	 * @return void
	 */
	protected function _assertSessionStackSize(array $options) {
		$messages = (array)$this->_session->read('Flash.' . $options['key']);
		if ($messages && count($messages) > $this->config('limit')) {
			array_shift($messages);
		}
		$this->_session->write('Flash.' . $options['key'], $messages);
	}

	/**
	 * Adds a transient flash message.
	 * These flash messages that are not saved (only available for current view),
	 * will be merged into the session flash ones prior to output.
	 *
	 * @param string $message Message to output.
	 * @param array|string|null $options Options
	 * @return void
	 */
	public function transientMessage($message, $options = null) {
		$options = $this->_mergeOptions($options);

		list($plugin, $element) = pluginSplit($options['element']);

		if ($plugin) {
			$options['element'] = $plugin . '.Flash/' . $element;
		} else {
			$options['element'] = 'Flash/' . $element;
		}

		$messages = (array)Configure::read('TransientFlash.' . $options['key']);
		if ($messages && count($messages) > $this->config('limit')) {
			array_shift($messages);
		}
		$messages[] = [
			'type' => $options['type'],
			'message' => $message,
			'key' => $options['key'],
			'element' => $options['element'],
			'params' => $options['params']
		];
		Configure::write('TransientFlash.' . $options['key'], $messages);
	}

}
