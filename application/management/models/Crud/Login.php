<?php
/**
 * Model untuk table User Login
 * 
 * @author Kanwil
 */
 
class Management_Model_Crud_Login extends Management_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'user';
	protected $_tableName = 'logins';
    protected $_ignoreCols = array('is_active', 'id_team','lock','userlogin','print_mode');
	
	protected $_foreignKeys = array(
        'role_id' => array( // nama kolom yg merupakan foreign key
            'label' => 'Role',
            'schema' => 'user',
            'table' => 'roles', // nama table yang ditunjuk
            'field' => 'id', // nama kolom yang ditunjuk
            'display' => 'name', // nama kolom dari table yg dijadikan display option
        ),
	);
	protected $_customElements = array(
		'password' => array(
			'password',
			'label' => 'Password (Kosong = tidak diubah)',
			'required' => false,
		),
	);
	
	public function toRowArray($withId = false)
	{
		$values = parent::toRowArray($withId);
		if (!$values['password']) {
			unset($values['password']);
		} else {
			$values['password'] = sha1($values['password']); // hash di level app
		}
		return $values;
	}

    public function closelogin($id)
    {
        $table = new Zend_Db_Table('user.logins');
        $data = array(
            'is_active' => 0
        );

        $table->update($data, "id = '".$id."'");
    }

    public function getisactive($id)
    {
        $table = new Zend_Db_Table('user.logins');
        $query = $table->select()
                    ->from('user.logins', array('is_active', 'id_team'))
                    ->where("id_team = '".$id."'")
                    ->group(array('is_active', 'id_team'))
        ;

        $result = $table->fetchRow($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }
}
