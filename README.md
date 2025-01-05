<h1>Fintrack Backend</h1>

<p>Fintrack is a finance app that allows users to manage their finances and receive tips and tricks on how to manage money better.</p>
<p>This app is the backend, the frontend is done under Fintrack Frontend. First set up the backend then the frontend.</p>

<h2>Requirements</h2>
<p>Before running the application, ensure you have the following installed on your machine:</p>
<ul>
  <li><a href="https://www.docker.com/get-started">Docker</a></li>
  <li><a href="https://docs.docker.com/compose/install/">Docker Compose</a></li>
</ul>
<p><strong>Note:</strong> Laravel Sail is included with the project, so <strong>no need for a separate PHP installation.</strong></p>
<p>You will need composer to install the projects dependencies.</p>

<h2>Setting Up the Backend</h2>

<ol>
  <li>
    <p><strong>Clone the repository</strong></p>
    <pre><code>git clone https://github.com/owensanders/fintrack-backend.git
cd fintrack-backend</code></pre>
  </li>
  
  <li>
    <p><strong>Set up the environment file</strong></p>
    <p>Copy the <code>.env.example</code> file to <code>.env</code>:</p>
    <pre><code>cp .env.example .env</code></pre>
  </li>
  
  <li>
    <p><strong>Build the Docker containers</strong></p>
    <p>Run Sail to build and start the Docker containers:</p>
    <pre><code>./vendor/bin/sail up -d</code></pre>
    <p>This will start the Laravel Sail environment in detached mode, including PHP, MySQL, and other services.</p>
  </li>
  
  <li>
    <p><strong>Generate the application key</strong></p>
    <p>After setting up your environment, generate the Laravel application key:</p>
    <pre><code>./vendor/bin/sail artisan key:generate</code></pre>
  </li>
  
  <li>
    <p><strong>Set up the database</strong></p>
    <p>Run the migration command to create the necessary database tables:</p>
    <pre><code>./vendor/bin/sail artisan migrate</code></pre>
    <p>You can also seed the database with fake data by running:</p>
    <pre><code>./vendor/bin/sail artisan db:seed</code></pre>
  </li>
  
  <li>
    <p><strong>Stopping the containers</strong></p>
    <p>To stop the Laravel Sail containers, run:</p>
    <pre><code>./vendor/bin/sail down</code></pre>
    <p>This will stop and remove the containers, but the data will persist in the volumes.</p>
  </li>
</ol>
