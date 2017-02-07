<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $Bookmarks
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Bookmarks', [
            'foreignKey' => 'user_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
   
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email'], 'Ya existe un usuario con este correo electrónico.'));

        return $rules;
    }

    public function validatorDefault(Validator $validator)
    {
        $validator
              ->add('id', 'valid', ['rule' => 'numeric'])
              ->notEmpty('id', 'create');

        $validator
              ->requirePresence('first_name', 'create')
              ->notEmpty('first_name', 'Rellene este campo');

        $validator
              ->requirePresence('last_name', 'create')
              ->notEmpty('last_name', 'Rellene este campo');

        $validator
              ->add('email', 'valid', ['rule' => 'email', 'mesage' => 'Ingrese un corre electrónico valido.'])
              ->requirePresence('email', 'create')
              ->notEmpty('email', 'Por favor introduzca un email valido');

        $validator
              ->requirePresence('password', 'create')
              ->notEmpty('password', 'Rellene este campo', 'create');

    }

    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        $query
            ->select(['id', 'first_name', 'last_name', 'email', 'password', 'role'])
            ->where(['Users.active' => 1]);

        return $query;
    }

       public function recoverPassword($id)
    {
        $user = $this->get($id);
        return $user->password;
    }

     public function beforeDelete($event, $entity, $options)
    {
        if ($entity->role == 'admin')
        {
            return false;
        }
        return true;
    }
}
