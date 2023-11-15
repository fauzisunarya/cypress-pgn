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
    main: process.env.REACT_APP_CMS_BASEPATH || "https://cms-be.doorv3-dev.neuron.id/",
    uam: process.env.REACT_APP_UAM_BASEPATH || "https://uam-dev.apps.mypaas.telkom.co.id/"
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
}

export const service = {
  retail : {
    // basepath : import.meta.env.VITE_API_RETAIL_BASEPATH
    basepath : 'https://retail.doorv3-dev.neuron.id'
    // basepath : 'https://data-dev.neuron.id'
  },
  customer : {
    // basepath : import.meta.env.REACT_APP_LOYALTY_BASEPATH
    basepath : 'https://cp.doorv3-dev.neuron.id/'
  },
  feedback : {
    // basepath : import.meta.env.VITE_API_RETAIL_BASEPATH
    basepath : 'https://retail.doorv3-dev.neuron.id/'
    // basepath : 'https://data-dev.neuron.id'
  },
  config : {
    // basepath : import.meta.env.VITE_API_RETAIL_BASEPATH
    basepath : 'https://retail.doorv3-dev.neuron.id/'
    // basepath : 'https://data-dev.neuron.id'
  },
}


export const token = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzaWQiOiJzYmkyYzJobHNtMTJxZ2gzZWgzZGxzYnMzaiIsInN1YiI6IjciLCJleHAiOjE2OTk5NDI1NjksImNvZGUiOiIyMDIzMDAwMDAyIiwibmFtZSI6ImJ1ZGlhbmEgMTIzIDYiLCJlbWFpbCI6ImJ1ZGk5MjkyQHlvcG1haWwuY29tIiwibGFuZyI6ImlkIiwiYXBwaWQiOjgsImFwcG5hbWUiOiJkb29yIiwiaHR0cHM6XC9cL2hhc3VyYS5pb1wvand0XC9jbGFpbXMiOnsieC1oYXN1cmEtYWxsb3dlZC1yb2xlcyI6W10sIngtaGFzdXJhLWRlZmF1bHQtcm9sZSI6ImFkbWluIn0sImdyYW50cyI6WyJsb2dpbiJdLCJjYW5fY3JlYXRlIjoieWVzIiwiY2FuX21vZGlmeSI6InllcyIsImNhbl9kZWxldGUiOiJ5ZXMiLCJjYW5fYXV0aCI6InllcyIsImNhbl9hY2wiOiJ5ZXMiLCJmbGFnIjoxLCJ1cmwiOiJodHRwczpcL1wvdWFtLWRldi5uZXVyb24uaWQiLCJjb25maWciOiJ7XCJhcHBsaWNhdGlvblwiOntcIm5hbWVcIjpcImRvb3JcIixcImNvbXBhbnlcIjpcImRvb3IgbmV1cm9uXCIsXCJpY29uXCI6XCJodHRwczpcL1wvY2RuLm5ldXJvbndvcmtzLmNvLmlkXC9kb29ydjNcL2ltYWdlc1wvZmF2aWNvbi5pY29cIixcImltYWdlX2xvZ29cIjpcImh0dHBzOlwvXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcL2Rvb3J2M1wvaW1hZ2VzXC9mYXZpY29uLmljb1wiLFwiaW1hZ2VfbG9nb19uYW1lXCI6XCJodHRwczpcL1wvY2RuLm5ldXJvbndvcmtzLmNvLmlkXC9kb29ydjNcL2ltYWdlc1wvaG9yaXpvbnRhbC1sb2dvLnBuZ1wiLFwiaW1hZ2VfbG9hZGVyXCI6XCJodHRwczpcL1wvY2RuLm5ldXJvbndvcmtzLmNvLmlkXC9kb29ydjNcL2ltYWdlc1wvZmF2aWNvbi5pY29cIn0sXCJlbXBsb3llZVwiOntcImFwcGxpY2F0aW9uXCI6XCJkb29yLGRvb3Jtb2JpbGVcIn0sXCJhdHRlbmRhbmNlXCI6e1wiY3V0b2ZmXCI6IFwiMjZcIn0sXCJzdHlsZVwiOlwiZG9vclwifSIsInNhbHQiOiIwZDAwMmY3ZjliNTlkOTM3ZWE0NjRlYjI2YTZlOGY5ZmM5ODU4NDc4MjY3YjYxMDgxNTk0NTZlNjkxODI3NzAwIn0.o06QcjbUeFlhHevT-3P0tObU6obHXAhdIT0d5Tvft1mFy6HBVRM5C-Op_0AMrlNYVjIm2J7bsMVKHOO8rMWVyKa_dEm-vVW-OIpthZJhK5SdhBBXo_G8pYL6xuPeN4tywHVoyt_2eGg9zyAIDgeMfaMz1kVnrHwpEehC6NqTtYo';