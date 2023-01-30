import logo from './logo.svg';
import './App.css';
import {useEffect} from 'react';
import axios from 'axios';

function App() {


  useEffect(() => {
    const newUser = {
      "email": "aasdemail@email.com",
      "password": "1234Csa"
    }

    axios.post("http://localhost:9090/backend/server/src/index.php/register", newUser)
      .then(res => console.log(res.data))
      .catch((error) => {
        console.log(error , "User registration problem!")
      })
  }, [])
  

  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <p>
          Edit <code>src/App.js</code> and save to reload.
        </p>
        <a
          className="App-link"
          href="https://reactjs.org"
          target="_blank"
          rel="noopener noreferrer"
        >
          Learn React
        </a>
      </header>
    </div>
  );
}

export default App;
