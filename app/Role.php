<?php

/**
 * By Mustafa Gamal
 */


namespace App;

use Cartalyst\Sentinel\Permissions\PermissibleInterface;
use Cartalyst\Sentinel\Permissions\PermissibleTrait;
use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Roles\RoleInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model implements RoleInterface, PermissibleInterface
{
    use PermissibleTrait;
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    /**
     * {@inheritDoc}
     */
    protected $table = 'roles';
    //public $child_roles;
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'is_group',
        'child_roles',
        'permissions',
    ];

    /**
     * The Eloquent users model name.
     *
     * @var string
     */
    protected static $usersModel = 'App\User';//'Cartalyst\Sentinel\Users\EloquentUser';

    /**
     * The Users relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(static::$usersModel, 'role_users', 'role_id', 'user_id')->withTimestamps();
    }

    /**
     * Get mutator for the "permissions" attribute.
     *
     * @param  mixed  $permissions
     * @return array
     */
    public function getPermissionsAttribute($permissions)
    {
        return $permissions ? json_decode($permissions, true) : [];
    }

    /**
     * Set mutator for the "permissions" attribute.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function setPermissionsAttribute(array $permissions)
    {
        $this->attributes['permissions'] = $permissions ? json_encode($permissions) : '';
    }
    public function setGroupAttribute(array $roles)
    {
        $this->attributes['permissions'] = $roles ? json_encode($roles) : '';
    }
    /**
     * {@inheritDoc}
     */
    public function getRoleId()
    {
        return $this->getKey();
    }
    
    public function parent(){
        return $this->belongsTo('App\Role', 'parent_id');
    }

    /**
     * {@inheritDoc}
     */
    public function getRoleSlug()
    {
        return $this->slug;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * {@inheritDoc}
     */
    public static function getUsersModel()
    {
        return static::$usersModel;
    }

    /**
     * {@inheritDoc}
     */
    public static function setUsersModel($usersModel)
    {
        static::$usersModel = $usersModel;
    }

    /**
     * Dynamically pass missing methods to the permissions.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $methods = ['hasAccess', 'hasAnyAccess'];

        if (in_array($method, $methods)) {
            $permissions = $this->getPermissionsInstance();

            return call_user_func_array([$permissions, $method], $parameters);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * {@inheritDoc}
     */
    protected function createPermissions()
    {
        return new static::$permissionsClass($this->permissions);
    }
}
