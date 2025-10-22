# Use an official Node image
FROM node:18-alpine

# Set working directory
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of your app
COPY . .

# Expose Vite’s dev port
EXPOSE 5173

# Start the Vite dev server
CMD ["npm", "run", "dev"]
