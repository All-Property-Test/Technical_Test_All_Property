<?php

class baseObj
{
    public $mysql = null;
    protected $table = null; //correction : change private to protected.  reason : then it can be accessed from child(inherited) class and overwrite the variable if necessary

    public function __construct()
    {
        $this->mysql = new mysqli("localhost", "allproperty", "testing", "allproperty");
        if ($this->mysql->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->mysql->connect_errno . ") " . $this->mysql->connect_error;
        }
    }

    public function setTable($table)
    { // Add steTable method  | reason : necessary if we need to set table name explicitly from child class
        $this->table = $table;
    }

    public function getByWhere($where)
    { // Add this new method  | reason : this method will be need to retrieve a hdb or condo property with a certain PID value
        if (is_string($where)) {
            $wh = ' WHERE' . $where;
        } elseif (is_array($where)) {
            foreach ($where as $key => $value) {
                $arr[] = ' ' . $key . '=' . '\'' . $value . '\'';
            }
            $wh = ' WHERE' . implode(' AND ', $arr);
        }
        return $this->mysql->query("SELECT * FROM $this->table" . $wh);
    }

    public function get($id, $field)
    {
        return $this->mysql->query("SELECT $field FROM $this->table WHERE ID = $id"); //correction : change $table to $this->table, $table won't be recognized
    }

    public function getAll($id)
    {
        $res = $this->mysql->query("SELECT * FROM $this->table WHERE ID = $id"); //correction : change $table to $this->table, $table won't be recognized
        return $res->fetch_assoc();
    }
}

class propertyData extends baseObj
{
    public $id = null;
    public $type = null;
    public $title = null;
    public $address = null;
    public $bedroom = null;
    public $livingroom = null;
    public $diningroom = null;
    public $size = null;
    //  protected $hdbblock = null;    //correction : variable is moved to hdbData Class | reason: this particular variable should belong to hdbData class according to OOP way of thinking
    //  protected $swimmingPool = null; //correction : variable is moved to condoData Class | reason: this particular variable should belong to condoData class according to OOP way of thinking

    protected $table = 'Property'; //correction : change private to protected  | reason : this has to be protected  to overwrite the table variable from the parent class


    // Suggestion : This will be a better implementation because it will need to query the database only once and all the attributes are set and ready to retrieve.
    // Previous implementation needs to query the database multiple times to get various attributes of the class, which puts unnecessarily bigger load on database.

    public function setProperty($id, $field = "*")
    {
        $this->setTable('property');
        $result = $this->get($id, $field);
        $row = $result->fetch_assoc();
        $this->setId($row['ID'])
            ->setType($row['Type'])
            ->setTitle($row['Title'])
            ->setAddress($row['Address'])
            ->setBedroom($row['Bedroom'])
            ->setLivingRoom($row['Living_room'])
            ->setDiningRoom($row['Diningroom'])
            ->setSize($row['Size']);
    }

    //Simple Setters and Getters

    public function getProperty($id)
    {
        $this->setProperty($id);
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function setBedroom($bedroom)
    {
        $this->bedroom = $bedroom;
        return $this;
    }

    public function setLivingRoom($livingroom)
    {
        $this->livingroom = $livingroom;
        return $this;
    }

    public function setDiningRoom($diningroom)
    {
        $this->diningroom = $diningroom;
        return $this;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType($ID)
    {
        return $this->type;
    }

    public function getTitle($ID)
    {
        return $this->title;
    }

    public function getAddress($ID)
    {
        return $this->address;
    }

    public function getBedroom($ID)
    {
        return $this->bedroom;
    }

    public function getLivingroom($ID)
    {
        return $this->livingroom;
    }

    public function getDiningroom($ID)
    {
        return $this->diningroom;
    }
}

class hdbData extends propertyData
{
    protected $table = 'HDB'; //correction : change private to protected  reason : this has to be protected to overwrite the table variable from baseObj parent class, so data from HDB table are correctly retrieved when "get" function is called
    protected $HdbId = null;
    protected $hdbblock = null;


    // Suggestion : This will be a better implementation because it will need to query the database only once and all the attributes are set and ready to retrieve.
    // Previous implementation needs to query the database multiple times to get various attributes of the class, which puts unnecessarily bigger load on database.
    // For this example, this is only hdbblock attribute for the class. But in real world, there will be a lot more attributes.
    public function setHdb($PID)
    {
        $this->setTable('HDB');
        $result = $this->getByWhere(array('PID' => $PID));
        $row = $result->fetch_assoc();
        $this->setHdbId($row['ID'])
            ->setHDBBlock($row['HDBBlock']);
    }

    public function getHdb($PID)
    {
        $this->setHdb($PID);
        return $this;
    }

    public function setHdbId($id)
    {
        $this->HdbId = $id;
        return $this;
    }

    public function setHDBBlock($block)
    {
        $this->hdbblock = $block;
        return $this;
    }

    public function getHDBBlock()
    {
        return $this->hdbblock;
    }
}

class condoData extends propertyData
{
    protected $table = 'ConDO'; //correction : change private to protected  reason : this has to be protected to overwrite the table variable from baseObj parent class, so data from condo table are correctly retrieved when "get" function is called
    protected $CID = null;
    protected $swimmingpool = null; // correctin : moved from propertyData class  | reason : swimmingpool attribute only belongs to CondoData class

    // Suggestion : This will be a better implementation because it will need to query the database only once and all the attributes are set and ready to retrieve.
    // Previous implementation needs to query the database multiple times to get various attributes of the class, which puts unnecessarily bigger load on database.
    // For this example, this is only swimmingpool attribute for the class. But in real world, there will be a lot more attributes.
    public function setCondo($PID)
    {
        $this->setTable('Condo');
        $result = $this->getByWhere(array('PID' => $PID));
        $row = $result->fetch_assoc();
        $this->setCID($row['ID'])
            ->setSwimmingPool($row['SwimmingPool']);
    }

    public function getCondo($PID)
    {
        $this->setCondo($PID);
        return $this;
    }

    public function setCID($id)
    {
        $this->CID = $id;
        return $this;
    }

    public function getCID()
    {
        return $this->CID;
    }

    public function setSwimmingPool($pool)
    {
        $this->swimmingpool = $pool;
        return $this;
    }

    public function getSwimmingPool()
    {
        return $this->swimmingpool;
    }
}

// Unit Test for HDB
$obj = new hdbData();
$property = $obj->getProperty(1);
$hdb = $obj->getHdb($property->getId());

echo 'ID : ' . $property->getId() . '<br />';
echo 'Type :' . $property->getType() . '<br />';
echo 'Title :' . $property->getTitle() . '<br />';
echo 'Address :' . $property->getAddress() . '<br />';
echo 'Bedroom :' . $property->getBedroom() . '<br />';
echo 'Livingroom :' . $property->getLivingroom() . '<br />';
echo 'Dining room :' . $property->getDiningroom() . '<br />';
echo 'HDB Block : ' . $hdb->getHDBBlock() . '<br /><br />';


//Unit Test for Condo

$obj = new condoData();
$property = $obj->getProperty(2);
$hdb = $obj->getCondo($property->getId());

echo 'ID : ' . $property->getId() . '<br />';
echo 'Type :' . $property->getType() . '<br />';
echo 'Title :' . $property->getTitle() . '<br />';
echo 'Address :' . $property->getAddress() . '<br />';
echo 'Bedroom :' . $property->getBedroom() . '<br />';
echo 'Livingroom :' . $property->getLivingroom() . '<br />';
echo 'Dining room :' . $property->getDiningroom() . '<br />';
echo 'Swimming Pool : ' . $hdb->getSwimmingPool() . '<br />';



?>