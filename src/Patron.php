<?php
	 class Patron
		{
		private $name;
		private $id;

		function __construct($name, $id = NULL)
		{
			$this->name = $name;
			$this->id = $id;
		}

		function getName()
		{
			return $this->name;
		}

		function setName($name)
		{
			$this->name = $name;
		}

		function getId()
		{
			return $this->id;
		}

		function save()
		{
			$GLOBALS['DB']->exec(
				"INSERT INTO patrons
				(name)
				VALUES
				('{$this->getName()}');"
			);
			$this->id = $GLOBALS['DB']->lastInsertId();
		}

		static function getAll()
		{
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $patrons = array();
            foreach($returned_patrons as $patron) {
                $name = $patron['name'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
		}

		static function deleteAll()
		{
			$GLOBALS['DB']->exec("DELETE FROM patrons;");
		}

		function delete()
		{
			$GLOBALS['DB']->exec(
			"DELETE p, c
			FROM patrons p
			JOIN checkouts c ON (p.id = c.patron_id)
			WHERE p.id = {$this->getId()};");
		}

		static function find($search_id)
		{
			$found_patron = null;
			$patrons = Patron::getAll();
			foreach($patrons as $patron){
				$patron_id = $patron->getId();
				if ($patron_id == $search_id)
					{
						$found_patron = $patron;
					}
			} return $found_patron;
		}
		function update($new_name)
		{
			$GLOBALS['DB']->exec("UPDATE patrons SET name = '{$new_name}' WHERE id = {$this->getId()};");
			$this->setName($new_name);
		}

		function checkoutHistory()
		{
			
		}
	}
 ?>
