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

class Car extends Vehicle {
    use GPS, AirConditioning;

    public $doors = true;
    public $fuel = 0;

    public function __construct($wheels, $doors, $fuel) {
        parent::__construct($wheels);
        $this->doors = $doors;
        $this->fuel = $fuel;
    }

    public function startEngine() {
        if(parent::startEngine()) {
            if ($this->fuel > 0) {
                echo "Car is going.\n";
            } else {
                echo "Car has no fuel. Please refuel.\n";
            }
        } else {
            echo "Please close the doors before starting the engine!\n";
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

class Truck extends Vehicle implements FuelConsumption, MaintenanceSchedule {
    use GPS, AirConditioning;

    public $cargoCapacity;
    public $currentLoad = 0;
    public $fuelLevel;
    public $mileage;

    public function __construct($wheels, $cargoCapacity, $fuelLevel, $mileage) {
        parent::__construct($wheels);
        $this->cargoCapacity = $cargoCapacity;
        $this->fuelLevel = $fuelLevel;
        $this->mileage = $mileage;
    }

    public function canMove() {
        return $this->fuelLevel > 0 && $this->currentLoad <= $this->cargoCapacity;
    }

    public function fuelEfficiency() {
        // the truck's fuel efficiency is 2 km per liter and decreases by 0.1 km/l for every ton of cargo
        $baseEfficiency = 2; // km per liter
        $efficiencyLossPerTon = 0.1;
        $actualEfficiency = $baseEfficiency - ($this->currentLoad * $efficiencyLossPerTon);
        echo "Current fuel efficiency: $actualEfficiency km/l.\n";
    }

    public function nextScheduledMaintenance() {
        // maintenance is required every 15,000 km
        $maintenanceInterval = 15000;
        $nextMaintenance = $this->mileage + $maintenanceInterval - ($this->mileage % $maintenanceInterval);
        echo "Next scheduled maintenance at: $nextMaintenance km.\n";
    }

    // Additional methods to manage cargo and fuel
    public function loadCargo($amount) {
        if ($this->currentLoad + $amount <= $this->cargoCapacity) {
            $this->currentLoad += $amount;
            echo "Loaded $amount tons of cargo. Current load: $this->currentLoad tons.\n";
        } else {
            echo "Cannot load $amount tons of cargo. Exceeds cargo capacity.\n";
        }
    }

    public function unloadCargo($amount) {
        if ($this->currentLoad - $amount >= 0) {
            $this->currentLoad -= $amount;
            echo "Unloaded $amount tons of cargo. Current load: $this->currentLoad tons.\n";
        } else {
            echo "Cannot unload $amount tons of cargo. Not enough cargo to unload.\n";
        }
    }

    public function refuel($liters) {
        $this->fuelLevel += $liters;
        echo "Truck refueled with $liters liters. Current fuel level: $this->fuelLevel liters.\n";
    }
}

class ElectricCar extends Car {
    public $batteryCharge = 100;

    public function __construct($wheels, $doors, $batteryCharge) {
        parent::__construct($wheels, $doors, 0); // Electric cars start with no fuel
        $this->batteryCharge = $batteryCharge;
    }

    public function startEngine() {
        if (parent::startEngine()) {
            if ($this->batteryCharge > 0) {
                echo "Electric car is ready to go.\n";
            } else {
                echo "Electric car has no battery charge. Please charge the battery.\n";
            }
        }
    }

    public function canMove() {
        return $this->doors && $this->batteryCharge > 0;
    }
}

class Bus extends Vehicle implements FuelConsumption, MaintenanceSchedule {
    use AirConditioning;

    private $passengerCapacity;
    private $currentPassengers = 0;
    private $fuelLevel;
    private $mileage;

    public function __construct($wheels, $passengerCapacity, $fuelLevel, $mileage) {
        parent::__construct($wheels);
        $this->passengerCapacity = $passengerCapacity;
        $this->fuelLevel = $fuelLevel;
        $this->mileage = $mileage;
    }

    public function canMove() {
        return $this->fuelLevel > 0 && $this->currentPassengers <= $this->passengerCapacity;
    }

    public function nextScheduledMaintenance() {
        // maintenance is required every 10,000 km
        $maintenanceInterval = 10000;
        $nextMaintenance = $this->mileage + $maintenanceInterval - ($this->mileage % $maintenanceInterval);
        echo "Next scheduled maintenance at: $nextMaintenance km.\n";
    }

    public function fuelEfficiency() {
        // fuel efficiency decreases by 1% for every additional passenger
        $baseEfficiency = 5; // km per liter
        $efficiencyLossPerPassenger = 0.01;
        $actualEfficiency = $baseEfficiency * (1 - ($this->currentPassengers * $efficiencyLossPerPassenger));
        echo "Current fuel efficiency: $actualEfficiency km/l.\n";
    }

    // Additional methods to manage passengers and fuel
    public function boardPassengers($number) {
        if ($this->currentPassengers + $number <= $this->passengerCapacity) {
            $this->currentPassengers += $number;
            echo "$number passengers boarded the bus. Current passengers: $this->currentPassengers.\n";
        } else {
            echo "Not enough space for $number passengers.\n";
        }
    }

    public function disembarkPassengers($number) {
        if ($this->currentPassengers - $number >= 0) {
            $this->currentPassengers -= $number;
            echo "$number passengers have disembarked. Current passengers: $this->currentPassengers.\n";
        } else {
            echo "Cannot disembark $number passengers.\n";
        }
    }

    public function refuel($liters) {
        $this->fuelLevel += $liters;
        echo "Bus refueled with $liters liters. Current fuel level: $this->fuelLevel liters.\n";
    }
}

?>
