<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event ) //Permite que el usuario pueda registrarse
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['register']);
    }

    public function isAuthorized($user)
    {
        if(isset($user['role']) and $user['role'] === 'user')
        {
            if(in_array($this->request->action, ['home', 'view', 'logout']))
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

	public function index()
	{
        $users = $this->paginate($this->Users);
        $this->set('users', $users);
	}

    public function login()
    {
    	if($this->request->is('post'))
    	{
    		$user = $this->Auth->identify();
    		if($user)
    		{
    			$this->Auth->setUser($user);
    			return $this->redirect($this->Auth->redirectUrl());
    		}
    		else
    		{
    			$this->Flash->error('Datos son invalidos, por favor intente nuevamente', ['key' => 'auth']);
    		}
    	}

        if($this->Auth->user()) //si el usuario está autentificado
        {
            return $this->redirect(['controller' => 'users', 'action' => 'home']);
        }
    }

    public function logout()
    {
    	return $this->redirect($this->Auth->logout());
    }

    public function home()
    {
    	$this->render();
    }

	public function view($id)
	{
        $user = $this->Users->get($id);
        $this->set('user', $user);
	}

    public function register()
    {
        $user = $this->Users->newEntity();

        if($this->request->is('post'))
        {
             $user = $this->Users->patchEntity($user, $this->request->data);
             
             $user->role = 'user';

             if($this->Users->save($user))
             {
                $this->Flash->success('Cuenta creada correctamente');
                return $this->redirect(['controller' => 'Users', 'action' => 'home']);
             }
             else
             {
                $this->Flash->error('Ocurrió un error al crear la cuenta, por favor intente nuevamente');
             }
        }

        $this->set(compact('user'));
    }

    public function edit($id=null)
    {
        $user = $this->Users->get($id);

        if ($this->request->is(['patch', 'post', 'put']))
        {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user))
            {
                $this->Flash->success('El usuario ha sido modificado.');
                return $this->redirect(['action' => 'index']);
            }

            else
            {
                $this->Flash->error('El usuario no pudo ser modificado, por favor intente nuevamente.');
            }
        }

        $this->set(compact('user'));
    }

	public function add()
	{
		$user = $this->Users->newEntity();

		if($this->request->is('post'))
		{
             $user = $this->Users->patchEntity($user, $this->request->data);

             if($this->Users->save($user))
             {
             	$this->Flash->success('El usuario ha sido creado correctamente');
             	return $this->redirect(['controller' => 'Users', 'action' => 'index']);
             }
             else
             {
             	$this->Flash->error('El usuario no se pudo crear');
             }
		}

		$this->set(compact('user'));
	}

    public function delete($id = null)
    {
         $this->request->allowMethod(['post', 'delete']); //esto prohibe al usuario de eliminar un usuario mediante el metodo get
         $user = $this->Users->get($id);

         if($this->Users->delete($user))
         {
            $this->Flash->success('El usuario ha sido eliminado.');
         }

         else
         {
            $this->Flash->error('El usuario no pudo ser eliminado. Por favor, intente nuevamente');
         }

         return $this->redirect(['action' => 'index']);
    }
}

