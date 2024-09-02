const { HuggingFaceAPI } = require('huggingface');

// Initialize the client
const client = new HuggingFaceAPI({
    apiKey: process.env.HUGGINGFACE_API_KEY,
});

// Prepare the request
const request = {
    inputs: 'Hello, world!',
};

// Send the request
client.textGeneration(request).then(response => {
    console.log('Generated text:', response.data);
}).catch(err => {
    console.error('Error generating text:', err);
});
