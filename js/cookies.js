// gets a cookie
// c :: String -> String
export function getCookie(k) {
  return (document.cookie.match('(^|; )' + k + '=([^;]*)') || 0)[2];
}

// finds the top level settable for cookies
// http://rossscrivener.co.uk/blog/javascript-get-domain-exclude-subdomain
export const getCookieDomain = () => {
  let i = 0;
  let { domain } = document;
  const p = domain.split('.');
  const s = '_gd' + new Date().getTime();

  while (i < p.length - 1 && document.cookie.indexOf(s + '=' + s) === -1) {
    domain = p.slice(-1 - ++i).join('.');
    document.cookie = s + '=' + s + ';domain=' + domain + ';';
  }
  document.cookie =
    s + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;domain=' + domain + ';';
  return domain;
};

// setcookie :: String -> String -> Integer -> String
export function setCookie(n, v, ex) {
  // need an escape hatch where we check if we can set cookeis, and if not, return v
  document.cookie = `${n}=${v}; Path=/; domain=${getCookieDomain()}; ${
    ex ? 'expires=' + new Date(Date.now() + ex * 864e5).toGMTString() : ''
  }`;
  return v;
}

// curried version for session Only cookies
// setcookie :: String -> String ~> String
export const setSessionCookie = (n) => (v) => {
  document.cookie = `${n}=${v}; Path=/; domain=${getCookieDomain()};`;
  return v;
};