<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Bookmarks Controller
 *
 * @property \App\Model\Table\BookmarksTable $Bookmarks
 */
class BookmarksController extends AppController
{
    public function isAuthorized($user)
    {
        if(isset($user['role']) and $user['role'] === 'user')
        {
            if(in_array($this->request->action, ['add', 'index']))
            {
                return true;
            }
        }
        // Dar acceso a los enlaces que solo correspondan al usuario autenticado
        if(in_array($this->request->action, ['edit', 'delete'])) // Acciones que el usuario va a tener permiso
        {
            $id = $this->request->params['pass'][0]; // Parametro que viene con la peticion
            $bookmark = $this->Bookmarks->get($id); 
            if($bookmark->user_id == $user['id']) 
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'conditions' => ['user_id' => $this->Auth->user('id')],
            'order' => ['id' => 'desc']
        ];
        $bookmarks = $this->paginate($this->Bookmarks);

        $this->set(compact('bookmarks'));
        $this->set('_serialize', ['bookmarks']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bookmark = $this->Bookmarks->newEntity();
        if ($this->request->is('post')) 
        {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->data);
            $bookmark->user_id = $this->Auth->user('id');
            if ($this->Bookmarks->save($bookmark)) 
            {
                $this->Flash->success(__('El enlace ha sido creado.'));

                return $this->redirect(['action' => 'index']);
            }
            else
            {    
                $this->Flash->error(__('No se pudo guardar el enlace, intente nuevamente.'));
            }
        }
        
        $this->set(compact('bookmark'));
        
    }

    /**
     * Edit method
     *
     * @param string|null $id Bookmark id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bookmark = $this->Bookmarks->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->data);
            $bookmark->user_id = $this->Auth->user('id');
            if ($this->Bookmarks->save($bookmark)) 
            {
                $this->Flash->success('El enlace ha sido modificado correctamente.');
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error('El enlace no se pudo modificar, intente nuevamente.');
            }
        }
        $users = $this->Bookmarks->Users->find('list', ['limit' => 200]);
        $this->set(compact('bookmark'));
        $this->set('_serialize', ['bookmark']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Bookmark id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bookmark = $this->Bookmarks->get($id);
        if ($this->Bookmarks->delete($bookmark)) 
        {
            $this->Flash->success('El enlace ha sido eliminado.');
        } 
        else
        {
            $this->Flash->error('El enlace no pudo ser eliminado, por favor intente nuevamente.');
        }

        return $this->redirect(['action' => 'index']);
    }
}
