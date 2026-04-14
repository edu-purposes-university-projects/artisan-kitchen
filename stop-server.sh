#!/bin/bash

# Stop PHP development server on localhost:3000
if lsof -ti:3000 > /dev/null 2>&1; then
    echo "Stopping BALTACI Artisan Kitchen development server..."
    kill $(lsof -ti:3000)
    sleep 1
    echo "Server stopped successfully."
else
    echo "No server running on port 3000."
fi

