import axios from "axios"



export function LoginTest() {

  const user = {
    email: "test@test.com",
    password: "password123"
  }

  const loginHandler = () => {
    axios.post("http://localhost:9090/backend/server/src/index.php/login", user)
      .then(res => localStorage.setItem('userToken', res.data))
  }

  const logoutHandler = () => {
    localStorage.removeItem('userToken')
  }


  return (
    <>
      <button onClick={loginHandler}>Login Test</button>
      <button onClick={logoutHandler}>Logout Test</button>
    </>
  )
}
