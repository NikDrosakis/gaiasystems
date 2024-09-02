require('dotenv').config();
const express =require('express');
const router = express.Router();


const { ModelServiceClient } = require('@google-cloud/aiplatform');
const client = new ModelServiceClient();
// Prepare the request
const projectId = process.env.GOOGLE_CLOUD_PROJECT_ID;
const modelId = 'your-model-id';
const instance = { text: 'when Greece will take the gold metal in Olympic Games?' };

const request = {
    name: `projects/${projectId}/locations/us-central1/models/${modelId}:predict`,
    payload: { instance },
};
router.post('/predict', async (req, res) => {
    try {
        const { text } = req.body;

        const projectId = process.env.GOOGLE_CLOUD_PROJECT_ID;
        const modelId = 'gemini-1.0-pro'; // Replace with your actual model ID

        const endpoint = `projects/${projectId}/locations/us-central1/models/${modelId}`;

        const request = {
            endpoint,
            instances: [{ text }] // Adjust this based on your model's input format
        };

        // Now use the `ModelServiceClient` for predictions
        const response = await client.predict({endpoint, instances: [{text}]});

        // Extract the prediction result (adapt based on your model's output)
        const prediction = response.predictions[0];
        console.log('Prediction result:', prediction);

        res.json({ prediction });
    } catch (err) {
        console.error('Error making prediction:', err);
        res.status(500).json({ error: err.message });
    }
});

module.exports = router;