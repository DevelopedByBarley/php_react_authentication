import './App.css';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { Route, Routes } from 'react-router-dom';
import { CountryList } from './pages/CountryList';
import { CountrySingle } from './pages/CountrySingle';
import { LoginTest } from './components/LoginTest';
import 'bootstrap/dist/css/bootstrap.min.css';

function App() {

  const [countries, setCountries] = useState([]);



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
    <>
      <LoginTest />
      <Routes>
        <Route path='/' element={<CountryList />} />
        <Route path='/country-single/:countryId' element={<CountrySingle />} />
      </Routes>
    </>
  )
}

export default App;
