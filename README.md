🧾 CSV Handler

A simple web app to upload, store, and manage employee data from CSV files.
Built with Vue 3 + Vite (frontend), PHP + MySQL (backend), and Docker Compose.


# 1. Build and start all containers

```
docker-compose up --build
```

Then open:

Frontend → http://localhost:5173

Backend API → http://localhost:8888

# 2. Changing Ports

Edit ports inside docker-compose.yml

You will also need to change the frontend/.env BASE URL if you change the
backend port "8888:80" 

# 3. If you need to rebuild docker

```
docker-compose down -v
docker-compose up --build
```

# Future Improvements

- Implement user login / authorisation.
- Pagination for the employee list.
- Unit tests.
- Search for an employee / filtering employees.
- Allow multiple CSV uploads.
- API security and limiting requests.
- A nicer user interface.

