<?php
namespace FzySlimCore\Util;

class Params implements \Countable, \Iterator, \ArrayAccess {

    /**
     * @var array
     */
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Returns whether the data array's key exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Gets a key's value, if it exists, else returns $default
     * @param null $key
     * @param null $default
     * @return null
     */
    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $this->data;
        }
        return $this->has($key) ? $this->data[$key] : $default;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Sets a single key to a value
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Sets the whole data set
     * @param $data
     */
    public function setAll($data)
    {
        $this->data = $data;
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return current($this->data) !== false;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param $name string
     * @return mixed
     * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    function __get($name)
    {
        return $this->get($name);
    }

    /**
     * run when writing data to inaccessible members.
     *
     * @param $name string
     * @param $value mixed
     * @return void
     * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * is triggered by calling isset() or empty() on inaccessible members.
     *
     * @param $name string
     * @return bool
     * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * is invoked when unset() is used on inaccessible members.
     *
     * @param $name string
     * @return void
     * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    function __unset($name)
    {
        unset($this->data[$name]);
    }

    /**
     * Methods to make this object array accessor supported
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Methods to make this object array accessor supported
     * @param mixed $offset
     * @return null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset, null);
    }

    /**
     * Methods to make this object array accessor supported
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->set($offset, $value);
        }
    }

    /**
     * Methods to make this object array accessor supported
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }


}