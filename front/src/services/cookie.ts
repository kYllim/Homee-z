
export function GetCookie(Nom : string) : string {
  let Token = ''
  const Cookies = document.cookie.split(";");
  Cookies.forEach(Cookie => {
    let NameCookie = Cookie.split('=');
    if(NameCookie[0] == Nom){
      Token = NameCookie[1].toString()
    }
  })
  return Token
}

export function setCookie(Name : string, Value : string, Days : number) {
  const date = new Date();
  date.setTime(date.getTime() + (Days * 24 * 60 * 60 * 1000));
  const expires = "expires=" + date.toUTCString();
  document.cookie = `${Name}=${Value};${expires};secure;path=/`;
}

export function RemoveCookie(Nom : string) {
  document.cookie = `${Nom}=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
}