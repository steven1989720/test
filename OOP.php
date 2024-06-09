<?php
abstract class Vehicle {
    public $wheels;
    private $engineStatus = false;

    abstract public function canMove();

    public function __construct($wheels) {
        $this->wheels = $wheels;
    }

    public function startEngine() {
        if ($this->canMove()){
            $this->engineStatus = true;

            return true;
        }
        return false;
    }

    public function stopEngine() {
        $this->engineStatus = false;
    }

    public function getEngineStatus() {
        return $this->engineStatus;
    }
}

class Car extends Vehicle {
    public $doors = true;
    public $fuel = 0;

    public function __construct($wheels, $doors, $fuel) {
        parent::__construct($wheels);
        $this->doors = $doors;
        $this->fuel = $fuel;
    }

    public function startEngine() {
        if(parent::startEngine()) {
            echo "Car is going";
        } else {
            echo "Please close the doors before starting the engine!";
        }
    }

    public function canMove() {
        return $this->doors && $this->fuel > 0;
    }
}

class Bike extends Vehicle {
    public $ride = false;

    public function __construct($wheels, $ride) {
        parent::__construct($wheels);
        $this->ride = $ride;
    }

    public function canMove() {
        return $this->ride;
    }
}
