// Entities
class SensorData {
  constructor(temperature, humidity) {
    this.temperature = temperature;
    this.humidity = humidity;
  }

  getTemperature() {
    return this.temperature;
  }

  getHumidity() {
    return this.humidity;
  }
}

class TemperatureSensorData extends SensorData {
  constructor(temperature, humidity) {
    super(temperature, humidity);
  }

  // Additional method specific to the TemperatureSensorData subclass
  getTemperatureInKelvin() {
    return this.temperature + 273.15;
  }
}

// Use Cases
class DataProcessor {
  static convertToFahrenheit(celsius) {
    return (celsius * 9) / 5 + 32;
  }

  static calculateHeatIndex(temperature, humidity) {
    // Simplified heat index calculation
    return temperature + humidity;
  }
}

// Interface Adapters
class SensorDataAdapter {
  static adapt(sensorData) {
    return {
      temperature: DataProcessor.convertToFahrenheit(sensorData.getTemperature()),
      humidity: sensorData.getHumidity(),
    };
  }
}

// Frameworks and Drivers
class SensorAPI {
  static fetchSensorData() {
    // Mock sensor data fetching
    return new SensorData(25, 50); // Celsius, Percentage
  }
}

// Dependency Rule
class SensorController {
  static getProcessedSensorData() {
    const sensorData = SensorAPI.fetchSensorData();
    const adaptedData = SensorDataAdapter.adapt(sensorData);
    const heatIndex = DataProcessor.calculateHeatIndex(
      adaptedData.temperature,
      adaptedData.humidity
    );

    return {
      ...adaptedData,
      heatIndex,
    };
  }
}

/**
 * SensorController: Orchestrates the flow of data from the sensor to the final heat index calculation.
 * SensorData: Represents raw data from a sensor.
 * DataProcessor: Provides utility functions for data conversion and calculations.
 * SensorDataAdapter: Adapts sensor data to a format suitable for processing.
 * SensorAPI: Simulates an external API to fetch sensor data.
 */

// Example usage
const processedData = SensorController.getProcessedSensorData();
console.log(processedData);

const tempSensorData = new TemperatureSensorData(25, 50); // Celsius, Percentage
console.log(tempSensorData.getTemperatureInKelvin()); // Outputs temperature in Kelvin
