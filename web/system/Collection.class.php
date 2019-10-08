<?php


abstract class Collection {
	public $offset = 0;
	public $limit = 20;

	public $page = 1;
	public $pages = 0;
	public $found = 0;

	public $rows = array();

	protected $_values = array();
	protected $_tables = array();
	protected $_selects = array();
	protected $_wheres = array();

	protected $_leftJoins = array();
	protected $_groupBy = null;
	protected $_orderBy = null;


	abstract protected function build($result);

	public function set($name, $value) {
		$this->$name = $value;
	}
        
	/**
	 * Get an array with page numbers to show in pagers
	 */
	public function getVisiblePages() {
		if ($this->pages == 1) {
			return array(1);
		}
		$pages = array(1,$this->pages);
		foreach (range($this->page - 2, $this->page + 2) as $i) {
			if ($i>1 && $i<$this->pages) {
				$pages[] = $i;
			}
		}
		sort($pages);
		return $pages;
	}

	public function addWhere($where) {
		$this->_wheres[] = $where;
		return $this;
	}

	public function addLeftJoins(array $leftJoins) {
		$this->_leftJoins = array_merge($this->_leftJoins, $leftJoins);
	}

	protected function _leftJoins(array $leftJoins) {
		$this->_leftJoins = $leftJoins;
	}

	protected function _groupBy($group_by) {
		$this->_groupBy = $group_by;
	}

	private function _getSql() {
		$sql = "select SQL_CALC_FOUND_ROWS ".implode(', ',$this->_selects);
		$sql.= " from (".implode(', ', $this->_tables).") ";
		if ($this->_leftJoins) {
			foreach ($this->_leftJoins as $table=>$on) {
				$sql.= " left join (".$table.") on (".$on.") ";
			}
		}
		$sql.= " where ".implode(' and ', $this->_wheres);
		if ($this->_groupBy) {
			$sql.= " group by ".$this->_groupBy;
		}
		if ($this->_orderBy) {
			$sql.= " order by ".$this->_orderBy;
		}
 		$sql.= " limit ".$this->offset.", ".$this->limit;
 		return $sql;
	}


	public function setSort($sort) {
		if (!in_array($sort, array_keys(static::$sorts))) {
			throw new Exception('invalid sort');
		}
		$this->_orderBy = static::$sorts[$sort];
                return $this;
	}

	public function setPage($page) {
		$this->page = $page;
		$this->offset = $this->limit * ( $this->page - 1 );
		return $this;
	}

	public function setLimit($limit) {
		$this->limit = $limit;
		return $this;
	}

	public function execute($values = array()) {
		$values = array_merge($this->_values, $values);

		$q = DB::prepare($this->_getSql());
		DB::exec($values, $q);
		$this->found = DB::foundRows();
		$this->pages = ceil($this->found/$this->limit);
		while($obj = DB::fetch($q)) {
			$this->rows[] = $this->build($obj);
		}
		$this->rows = array_reverse($this->rows);
	}


}