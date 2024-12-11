Fintrack Backend
Fintrack is a finance app that allows users to manage their finances and receive tips and tricks on how to manage money better.

THIS PROJECT IS NOT COMPLETE.

Requirements
Before running the application, ensure you have the following installed on your machine:

Docker
Docker Compose
Laravel Sail is included with the project, so no need for a separate PHP installation.

Setting Up the Backend
1. Clone the repository
git clone https://github.com/your-username/fintrack-backend.git
cd fintrack-backend

2. Install dependencies
Run the following command to install the required PHP dependencies via Composer (inside the Sail container):
./vendor/bin/sail install

3. Set up the environment file
Copy the .env.example file to .env:
cp .env.example .env

4. Build the Docker containers
Run Sail to build and start the Docker containers:
./vendor/bin/sail up -d

This will start the Laravel Sail environment in detached mode, including PHP, MySQL, and other services.

5. Generate application key
After setting up your environment, generate the Laravel application key:
./vendor/bin/sail artisan key:generate

6. Set up the database
Run the migration command to create the necessary database tables:
./vendor/bin/sail artisan migrate
You can also seed the database with fake data by running:
./vendor/bin/sail artisan db:seed

7. Stopping the containers
To stop the Laravel Sail containers, run:
./vendor/bin/sail down

This will stop and remove the containers, but the data will persist in the volumes.
