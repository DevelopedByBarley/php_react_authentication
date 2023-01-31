import axios from "axios";
import { useEffect, useState } from "react";
import { useParams } from "react-router-dom"

export function CountrySingle() {
  const params = useParams();
  const countryId = params.countryId;
  const [singleCountry, setSingleCountry] = useState("");
  const [citiesOfCountry, setCitiesOfCountry] = useState([]);

  useEffect(() => {
    const userToken = localStorage.getItem('userToken');
    if (userToken) {
      axios.get(`http://localhost:9090/backend/server/src/index.php/country-single?countryId=${countryId}`, {
        headers: { Authorization: `Bearer ${userToken}` }
      })
        .then((res) => {
          setSingleCountry(res.data.country);
          setCitiesOfCountry(res.data.cities);
        })
    }
    else {
      console.log("Nincs bejelentkezve!")
    }
  }, [])

  return (
    <div className="country">
      <h1>{singleCountry.name}</h1>

      <div className="cities mt-5 mb-5 border">
        <h1 >Városok:</h1>
        <div class="btn-group-sm m-2">
          {citiesOfCountry?.map((city) => {
            return <button className="btn btn-outline-primary m-1">{city.name}</button>
          })}
        </div>
      </div>

    </div>


  )
}
