# Use a Node.js base image
FROM node:20-alpine

# Set the working directory inside the container
WORKDIR /app

# Copy package.json and package-lock.json (if you have one)
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of your application code
COPY . .
COPY ../core ./core/
# Build your React app (adjust the build command if needed)
RUN npm run build

# Expose the port your React app will run on
EXPOSE 3000

# Start the production server (adjust command if needed)
CMD ["npm", "run", "start"]