import logo from './logo.svg';
import './App.css';
import { useEffect, useState } from 'react';
import axios from 'axios';

function App() {

  const [countries, setCountries] = useState([]);

  const user = {
    email: "test@test.com",
    password: "password123"
  }

  const loginHandler = () => {
    axios.post("http://localhost:9090/backend/server/src/index.php/login", user)
      .then(res => localStorage.setItem('userToken', res.data))
  }

  useEffect(() => {
    const userToken = localStorage.getItem('userToken');
    if (userToken) {
      axios.get('http://localhost:9090/backend/server/src/index.php', {
        headers: { Authorization: `Bearer ${userToken}` }
      })
        .then(res => setCountries(res.data))
    }
  }, [])

  return (
    <div className='countries'>
      {countries.map((country) => {
        return (
          <h1 key={country.id}>{country.name}</h1>
        )
      })}
      <button onClick={loginHandler}>Login</button>
    </div>
  )
}

export default App;
