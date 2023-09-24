// routes
import { PATH_DASHBOARD } from './routes/paths';

// API
// ----------------------------------------------------------------------

export const HOST_API_KEY = process.env.REACT_APP_HOST_API_KEY || '';

export const FIREBASE_API = {
  apiKey: process.env.REACT_APP_FIREBASE_API_KEY,
  authDomain: process.env.REACT_APP_FIREBASE_AUTH_DOMAIN,
  projectId: process.env.REACT_APP_FIREBASE_PROJECT_ID,
  storageBucket: process.env.REACT_APP_FIREBASE_STORAGE_BUCKET,
  messagingSenderId: process.env.REACT_APP_FIREBASE_MESSAGING_SENDER_ID,
  appId: process.env.REACT_APP_FIREBASE_APPID,
  measurementId: process.env.REACT_APP_FIREBASE_MEASUREMENT_ID,
};

export const COGNITO_API = {
  userPoolId: process.env.REACT_APP_AWS_COGNITO_USER_POOL_ID,
  clientId: process.env.REACT_APP_AWS_COGNITO_CLIENT_ID,
};

export const AUTH0_API = {
  clientId: process.env.REACT_APP_AUTH0_CLIENT_ID,
  domain: process.env.REACT_APP_AUTH0_DOMAIN,
};

export const MAPBOX_API = process.env.REACT_APP_MAPBOX_API;

// ROOT PATH AFTER LOGIN SUCCESSFUL
export const PATH_AFTER_LOGIN = PATH_DASHBOARD.one;

// LAYOUT
// ----------------------------------------------------------------------

export const HEADER = {
  H_MOBILE: 64,
  H_MAIN_DESKTOP: 88,
  H_DASHBOARD_DESKTOP: 92,
  H_DASHBOARD_DESKTOP_OFFSET: 92 - 32,
};

export const NAV = {
  W_BASE: 260,
  W_DASHBOARD: 280,
  W_DASHBOARD_MINI: 88,
  //
  H_DASHBOARD_ITEM: 48,
  H_DASHBOARD_ITEM_SUB: 36,
  //
  H_DASHBOARD_ITEM_HORIZONTAL: 32,
};

export const ICON = {
  NAV_ITEM: 24,
  NAV_ITEM_HORIZONTAL: 22,
  NAV_ITEM_MINI: 22,
};

export const api = {
  basepath:{
    main: process.env.REACT_APP_CMS_BASEPATH || "https://cms.doorv3-dev.neuron.id/",
    uam: process.env.REACT_APP_UAM_BASEPATH || "https://uam-dev.apps.mypaas.telkom.co.id/"
  }
}

export const auth = {
  otp: process.env.REACT_APP_UAM_OTP || "email",
  captcha: process.env.REACT_APP_UAM_CAPTCHA || false,
  application: process.env.REACT_APP_UAM_APP || '',
  secret: process.env.REACT_APP_UAM_SECRET || ''
}

export const personalization = {
  application: 'Neuron React Skeleton',
  logo: 'https://git.neuron.id/uploads/-/system/appearance/header_logo/1/logomark-symbol__5_.png',
  logo_light : '',
  logo_full : 'https://hrmis.neuron.id/assets/personalization/7e98d4b22000db84ad167311140ee6b1.png',
  theme_color : '',
}

export const token = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzaWQiOiIiLCJzdWIiOiIxNSIsImV4cCI6MTY5NTYwMDA2MCwiY29kZSI6IjIwMjMwMDAwMDQiLCJuYW1lIjoiYnVkaWFuYSBjZWsiLCJlbWFpbCI6ImFkamlwYW5nZXN0dUBuZXVyb253b3Jrcy5jby5pZCIsImxhbmciOiJpZCIsImFwcGlkIjo4LCJhcHBuYW1lIjoiZG9vciIsImh0dHBzOlwvXC9oYXN1cmEuaW9cL2p3dFwvY2xhaW1zIjp7IngtaGFzdXJhLWFsbG93ZWQtcm9sZXMiOlsiaHIiXSwieC1oYXN1cmEtZGVmYXVsdC1yb2xlIjoiaHIifSwiZ3JhbnRzIjpbImxvZ2luIiwibW51X2FwaSIsImNtc19jcmVhdGVfY29udGVudCIsImNtc19kZWxldGVfY29udGVudCIsImNtc19saXN0X2NvbnRlbnQiLCJjcmVhdGVfcmV3YXJkIiwidXBkYXRlX3JlZGVlbV9zdGF0dXMiLCJ1cGRhdGVfcmV3YXJkIiwidXBkYXRlX3N0YXR1c19wZW5jYXRhdGFuIiwidXNlcl9hdmF0YXIiLCJ1c2VyX2NyZWF0ZSIsInVzZXJfcHJvZmlsZSIsInVzZXJfdXBkYXRlIiwidmlld19jdXN0b21lcl9pbmZvIiwidmlld19yZWNvcmRpbmdfbGlzdCIsInZpZXdfcmVkZWVtX2xpc3QiXSwiY2FuX2NyZWF0ZSI6InllcyIsImNhbl9tb2RpZnkiOiJ5ZXMiLCJjYW5fZGVsZXRlIjoieWVzIiwiY2FuX2F1dGgiOiJ5ZXMiLCJjYW5fYWNsIjoieWVzIiwiZmxhZyI6MSwidXJsIjoiaHR0cHM6XC9cL3VhbS1kZXYubmV1cm9uLmlkIiwiY29uZmlnIjoie1wiYXBwbGljYXRpb25cIjp7XCJuYW1lXCI6XCJkb29yXCIsXCJjb21wYW55XCI6XCJkb29yIG5ldXJvblwiLFwiaWNvblwiOlwiaHR0cHM6XC9cL2Nkbi5uZXVyb253b3Jrcy5jby5pZFwvZG9vcnYzXC9pbWFnZXNcL2Zhdmljb24uaWNvXCIsXCJpbWFnZV9sb2dvXCI6XCJodHRwczpcL1wvY2RuLm5ldXJvbndvcmtzLmNvLmlkXC9kb29ydjNcL2ltYWdlc1wvZmF2aWNvbi5pY29cIixcImltYWdlX2xvZ29fbmFtZVwiOlwiaHR0cHM6XC9cL2Nkbi5uZXVyb253b3Jrcy5jby5pZFwvZG9vcnYzXC9pbWFnZXNcL2hvcml6b250YWwtbG9nby5wbmdcIixcImltYWdlX2xvYWRlclwiOlwiaHR0cHM6XC9cL2Nkbi5uZXVyb253b3Jrcy5jby5pZFwvZG9vcnYzXC9pbWFnZXNcL2Zhdmljb24uaWNvXCJcclxufSxcImVtcGxveWVlXCI6e1wiYXBwbGljYXRpb25cIjpcImRvb3IsZG9vcm1vYmlsZVwifSxcImF0dGVuZGFuY2VcIjp7XCJjdXRvZmZcIjogXCIyNlwifSxcInN0eWxlXCI6XCJwZ25cIn0iLCJzYWx0IjoiYzBiZWM2ZWUzNjliOTk1ZTU0ZDRjNjI5ODRiOTA5YzhhMmJlNzU4NDU5YjQ1Njk3ZWEyNDhhYzQ0YTg2ZDQwMyJ9.jtlY-_T_VNT9_UexVoBUyrf2SzHzKUOHlKUf1ojXWQYcaPto6kojA9swlmS7Eyz9_VXGROZNbpSv0HgR1PCs3AZitWuuhth_ngMCNiQ_4p3D7UEd294NjafBjD6z_l0p0J38gkJwSC6eafKGgts46g5uaBH_lhdkY_h6fM8u1Tc';