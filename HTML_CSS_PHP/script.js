function Get() {
    var arr = { key1: 'val1', key2: 'val2', key3: 'val3' };
    var data = JSON.stringify(arr);

    // Use fetch API to send a POST request
    fetch('get.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: data // Convert the arr object to a JSON string
    })
    .then(response => {
        return response.text();
    }) // Parse the text response
    .then(data => {
        alert(data); // Alert with the response data
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
