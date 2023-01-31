import { useEffect, useState } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';


export function CountryList() {
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
    <div className="countryList">
      <div className='countries list-group'>
        {countries.map((country) => {
          return (
            <Link to={`/country-single/${country.id}`}>
              <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">
                 Ország neve: {country.name}
                </h5>
                <small>Kontinens: {country.continent}</small>
              </div>
              <small>Népesség: {country.population} fő</small>
            </Link>
          )
        })}
      </div>
    </div>
  )
}
