// routes
// import { PATH_DASHBOARD } from './routes/paths';

// API
// ----------------------------------------------------------------------

// export const HOST_API_KEY = process.env.REACT_APP_HOST_API_KEY || '';

// export const FIREBASE_API = {
//   apiKey: process.env.REACT_APP_FIREBASE_API_KEY,
//   authDomain: process.env.REACT_APP_FIREBASE_AUTH_DOMAIN,
//   projectId: process.env.REACT_APP_FIREBASE_PROJECT_ID,
//   storageBucket: process.env.REACT_APP_FIREBASE_STORAGE_BUCKET,
//   messagingSenderId: process.env.REACT_APP_FIREBASE_MESSAGING_SENDER_ID,
//   appId: process.env.REACT_APP_FIREBASE_APPID,
//   measurementId: process.env.REACT_APP_FIREBASE_MEASUREMENT_ID,
// };

// export const COGNITO_API = {
//   userPoolId: process.env.REACT_APP_AWS_COGNITO_USER_POOL_ID,
//   clientId: process.env.REACT_APP_AWS_COGNITO_CLIENT_ID,
// };

// export const AUTH0_API = {
//   clientId: process.env.REACT_APP_AUTH0_CLIENT_ID,
//   domain: process.env.REACT_APP_AUTH0_DOMAIN,
// };

// export const MAPBOX_API = process.env.REACT_APP_MAPBOX_API;

// ROOT PATH AFTER LOGIN SUCCESSFUL
// export const PATH_AFTER_LOGIN = PATH_DASHBOARD.one;

// LAYOUT
// ----------------------------------------------------------------------

export const HEADER = {
  H_MOBILE: 64,
  H_MAIN_DESKTOP: 88,
  H_DASHBOARD_DESKTOP: 60,
  H_DASHBOARD_DESKTOP_OFFSET: 60 - 32,
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
    main: "https://63aafbf8cf281dba8c1697c5.mockapi.io/aryaapi",
    uam: import.meta.env.VITE_APP_UAM_BASEPATH || "https://uam.doorv3-dev.neuron.id"
  }
}

export const auth = {
  otp: "email",
  captcha: false,
  application: '',
  secret: ''
}

export const personalization = {
  application: 'Neuron React Skeleton',
  logo: 'https://git.neuron.id/uploads/-/system/appearance/header_logo/1/logomark-symbol__5_.png',
  logo_light : '',
  logo_full : 'https://hrmis.neuron.id/assets/personalization/7e98d4b22000db84ad167311140ee6b1.png',
  theme_color : '',
  style_name : '',
}

export const service = {
  cms : {
    // basepath : import.meta.env.REACT_APP_LOYALTY_BASEPATH
    // basepath : import.meta.env.REACT_APP_CMS_BASEPATH  || 'https://dev-cms-pgnmobile.pgn.co.id'
    basepath : 'http://pgnmobile-contentmanagementservice.test'
  },
  proxy : {
    basepath : import.meta.env.REACT_APP_PROXY_BASEPATH || 'https://dev-proxy-pgnmobile.pgn.co.id'
  },
  customer : {
    basepath : import.meta.env.REACT_APP_CUSTOMER_BASEPATH || 'https://dev-cp-pgnmobile.pgn.co.id'
  },
  uam : {
    basepath : import.meta.env.REACT_APP_UAM_BASEPATH || 'https://dev-uam-pgnmobile.pgn.co.id'
  }
}


export const token = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzaWQiOiJxaXNmaWJhdTJscGZrbjNwdXIwa3VjYjc3YSIsInN1YiI6IjE1IiwiZXhwIjoxNjk3MDg1NDM4LCJjb2RlIjoiMjAyMzAwMDAwNCIsIm5hbWUiOiJidWRpYW5hIGNlayIsImVtYWlsIjoiYWRqaXBhbmdlc3R1QG5ldXJvbndvcmtzLmNvLmlkIiwibGFuZyI6ImlkIiwiYXBwaWQiOjgsImFwcG5hbWUiOiJkb29yIiwiaHR0cHM6XC9cL2hhc3VyYS5pb1wvand0XC9jbGFpbXMiOnsieC1oYXN1cmEtYWxsb3dlZC1yb2xlcyI6WyJociJdLCJ4LWhhc3VyYS1kZWZhdWx0LXJvbGUiOiJociJ9LCJncmFudHMiOlsibG9naW4iLCJtbnVfYXBpIiwiY21zX2NyZWF0ZV9jb250ZW50IiwiY21zX2RlbGV0ZV9jb250ZW50IiwiY21zX2xpc3RfY29udGVudCIsImNyZWF0ZV9yZXdhcmQiLCJzZW5kX25vdGlmIiwidXBkYXRlX3JlZGVlbV9zdGF0dXMiLCJ1cGRhdGVfcmV3YXJkIiwidXBkYXRlX3N0YXR1c19wZW5jYXRhdGFuIiwidXNlcl9hdmF0YXIiLCJ1c2VyX2NyZWF0ZSIsInVzZXJfcHJvZmlsZSIsInVzZXJfdXBkYXRlIiwidmlld19jdXN0b21lcl9pbmZvIiwidmlld19yZWNvcmRpbmdfbGlzdCIsInZpZXdfcmVkZWVtX2xpc3QiLCJtbnVfbm90aWZfdGVtcGxhdGUiLCJtbnVfbG95YWx0eV9jYW1wYWlnbiIsIm1udV9jbXNfY29udGVudCJdLCJjYW5fY3JlYXRlIjoieWVzIiwiY2FuX21vZGlmeSI6InllcyIsImNhbl9kZWxldGUiOiJ5ZXMiLCJjYW5fYXV0aCI6InllcyIsImNhbl9hY2wiOiJ5ZXMiLCJmbGFnIjoxLCJ1cmwiOiJodHRwczpcL1wvdWFtLWRldi5uZXVyb24uaWQiLCJjb25maWciOiJ7XCJhcHBsaWNhdGlvblwiOntcIm5hbWVcIjpcImRvb3JcIixcImNvbXBhbnlcIjpcImRvb3IgbmV1cm9uXCIsXCJpY29uXCI6XCJodHRwczpcL1wvY2RuLm5ldXJvbndvcmtzLmNvLmlkXC9kb29ydjNcL2ltYWdlc1wvZmF2aWNvbi5pY29cIixcImltYWdlX2xvZ29cIjpcImh0dHBzOlwvXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcL2Rvb3J2M1wvaW1hZ2VzXC9mYXZpY29uLmljb1wiLFwiaW1hZ2VfbG9nb19uYW1lXCI6XCJodHRwczpcL1wvY2RuLm5ldXJvbndvcmtzLmNvLmlkXC9kb29ydjNcL2ltYWdlc1wvaG9yaXpvbnRhbC1sb2dvLnBuZ1wiLFwiaW1hZ2VfbG9hZGVyXCI6XCJodHRwczpcL1wvY2RuLm5ldXJvbndvcmtzLmNvLmlkXC9kb29ydjNcL2ltYWdlc1wvZmF2aWNvbi5pY29cIiIsInNhbHQiOiIzZjMxNmFiY2UyYjM5MzAwMDA5N2E4MDJkNmFjNmVmODczODYwZmJmMDNlZmU2NmVhMzQzYTZhMTE3NzZjM2RlIn0.EtNtSERc-MLJgSt4k4auHPWldFAxP0440aoP5ens09lC_QoZmH61gx5fczQa7R_I-ews9Nd0QFi4fQU324stSZuyE2EasDw8idqqkrdnmkB7M2dpUIIOs_o9Nh-hf42vrr-NeFtFxj0E9bMeZSJuVJV8DnyFMUrIxa1cTJtt6qM';

