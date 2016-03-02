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

		static function delete()
		{
			$GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->findId()};");
		}
	}
 ?>
