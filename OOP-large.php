<?php
interface FuelConsumption {
    public function fuelEfficiency();
}

interface MaintenanceSchedule {
    public function nextScheduledMaintenance();
}

trait GPS {
    public function getCurrentLocation() {
        echo "Current location from GPS";
    }
}

trait AirConditioning {
    public function toggleAC($status) {
        echo "Turns the AC on or off";
    }
}

abstract class Vehicle {
    public $wheels;
    private $engineStatus = false;
    private $speed = 0;

    abstract public function canMove();

    public function __construct($wheels) {
        $this->wheels = $wheels;
    }

    public function startEngine() {
        if ($this->canMove()){
            $this->engineStatus = true;
            echo "Engine started.\n";
            return true;
        }
        echo "Cannot start engine. Check if the vehicle is ready to move.\n";
        return false;
    }

    public function stopEngine() {
        $this->engineStatus = false;
        $this->speed = 0;
        echo "Engine stopped.\n";
    }

    public function getEngineStatus() {
        return $this->engineStatus;
    }

    public function accelerate($amount) {
        if ($this->engineStatus) {
            $this->speed += $amount;
            echo "Vehicle accelerated by $amount km/h. Current speed: $this->speed km/h.\n";
        } else {
            echo "Cannot accelerate. Engine is not started.\n";
        }
    }

    public function brake($amount) {
        if ($this->speed > 0) {
            $this->speed -= $amount;
            if ($this->speed < 0) {
                $this->speed = 0;
            }
            echo "Vehicle slowed down by $amount km/h. Current speed: $this->speed km/h.\n";
        } else {
            echo "Vehicle is already stationary.\n";
        }
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

class DoorVehicle extends Vehicle {
    use GPS, AirConditioning;

    public $doors = true;

    public function __construct($wheels, $doors) {
        parent::__construct($wheels);
        $this->doors = $doors;
    }
    
    public function canMove() {
        return $this->doors;
    }
}

class ElectricCar extends DoorVehicle {
    public $batteryCharge = 100;

    public function __construct($wheels, $doors, $batteryCharge) {
        parent::__construct($wheels, $doors);
        $this->batteryCharge = $batteryCharge;
    }

    public function startEngine() {
        if ($this->hasBattery()) {
            parent::startEngine();
            echo "Electric car is ready to go.\n";
        } else {
            echo "Electric car has no battery charge. Please charge the battery.\n";
        }
    }

    public function canMove() {
        return parent::canMove() && $this->hasBattery();
    }

    protected function hasBattery() {
        return $this->batteryCharge > 0;
    }
}

class FuelVehicle extends DoorVehicle implements MaintenanceSchedule, FuelConsumption {
    public $fuelLevel = 0;
    public $mileage = 0;
    public $maintenanceInterval = 15000;// maintenance is required every 15,000 km

    public function __construct($wheels, $doors, $fuelLevel, $mileage, $maintenanceInterval) {
        parent::__construct($wheels, $doors);

        $this->fuelLevel = $fuelLevel;
        $this->mileage = $mileage;
        $this->maintenanceInterval = $maintenanceInterval;
    }

    public function startEngine() {
        if(parent::startEngine()) {
            if ($this->fuelLevel > 0) {
                echo "Car is going.\n";
            } else {
                echo "Car has no fuel. Please refuel.\n";
            }
        } else {
            echo "Please close the doors before starting the engine!\n";
        }
    }

    public function canMove() {
        return parent::canMove() && $this->fuelLevel > 0;
    }

    public function nextScheduledMaintenance() {
        return $this->mileage + $this->maintenanceInterval - ($this->mileage % $this->maintenanceInterval);
    }

    public function fuelEfficiency() {
        return $this->calcFuelEfficiency();
    }

    public function refuel($liters) {
        $this->fuelLevel += $liters;
    }

    protected function calcFuelEfficiency() {
        return 1;
    }
}

class Truck extends FuelVehicle {
    public $cargoCapacity;
    public $currentLoad = 0;

    public function __construct($wheels, $doors, $fuelLevel, $mileage, $cargoCapacity) {
        parent::__construct($wheels, $doors, $fuelLevel, $mileage, 15000);

        $this->cargoCapacity = $cargoCapacity;
    }

    public function canMove() {
        return parent::canMove() && $this->currentLoad <= $this->cargoCapacity;
    }

    protected function calcFuelEfficiency() {
        // the truck's fuel efficiency is 2 km per liter and decreases by 0.1 km/l for every ton of cargo
        $baseEfficiency = 2; // km per liter
        $efficiencyLossPerTon = 0.1;

        return $baseEfficiency - ($this->currentLoad * $efficiencyLossPerTon);
    }

    // Additional methods to manage cargo and fuel
    public function loadCargo($amount) {
        if ($this->currentLoad + $amount <= $this->cargoCapacity) {
            $this->currentLoad += $amount;
        } else {
            echo "Cannot load $amount tons of cargo. Exceeds cargo capacity.\n";
        }
    }

    public function unloadCargo($amount) {
        if ($this->currentLoad - $amount >= 0) {
            $this->currentLoad -= $amount;
        } else {
            echo "Cannot unload $amount tons of cargo. Not enough cargo to unload.\n";
        }
    }
}

class Bus extends FuelVehicle {
    private $passengerCapacity;
    private $currentPassengers = 0;

    public function __construct($wheels, $doors, $fuelLevel, $mileage, $passengerCapacity) {
        parent::__construct($wheels, $doors, $fuelLevel, $mileage, 10000);

        $this->passengerCapacity = $passengerCapacity;
    }

    public function canMove() {
        return parent::canMove() && $this->currentPassengers <= $this->passengerCapacity;
    }

    protected function calcFuelEfficiency() {
        // fuel efficiency decreases by 1% for every additional passenger
        $baseEfficiency = 5; // km per liter
        $efficiencyLossPerPassenger = 0.01;
        
        return $baseEfficiency * (1 - ($this->currentPassengers * $efficiencyLossPerPassenger));
    }

    // Additional methods to manage passengers and fuel
    public function boardPassengers($number) {
        if ($this->currentPassengers + $number <= $this->passengerCapacity) {
            $this->currentPassengers += $number;
        } else {
            echo "Not enough space for $number passengers.\n";
        }
    }

    public function disembarkPassengers($number) {
        if ($this->currentPassengers - $number >= 0) {
            $this->currentPassengers -= $number;
        } else {
            echo "Cannot disembark $number passengers.\n";
        }
    }
}
