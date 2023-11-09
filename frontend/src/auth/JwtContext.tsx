import { createContext, useEffect, useReducer, useCallback } from 'react';
// utils
import axios from '../utils/axios';
//
import { isValidToken, jwtDecode, setSession } from './utils';
import { ActionMapType, AuthStateType, AuthUserType, JWTContextType } from './types';
import { login as login_handler } from 'src/api_handler/auth';
// import { forgotPassword as forgot_password_handler } from 'src/api_handler/auth';
// import { submitNewPassword as new_password_handler } from 'src/api_handler/auth';
// import { logout as logout_handler } from 'src/api_handler/auth';
import useApm  from "@elastic/apm-rum";
import axiosContent from '../utils/axios';

// ----------------------------------------------------------------------

// NOTE:
// We only build demo at basic level.
// Customer will need to do some extra handling yourself if you want to extend the logic and other features...

// ----------------------------------------------------------------------

enum Types {
  INITIAL = 'INITIAL',
  LOGIN = 'LOGIN',
  REGISTER = 'REGISTER',
  LOGOUT = 'LOGOUT',
  FORGOT_PASSWORD = 'FORGOT_PASSWORD',
  NEW_PASSWORD = 'NEW_PASSWORD'
}

type Payload = {
  [Types.INITIAL]: {
    isAuthenticated: boolean;
    user: AuthUserType;
  };
  [Types.LOGIN]: {
    user: AuthUserType;
  };
  [Types.REGISTER]: {
    user: AuthUserType;
  };
  [Types.LOGOUT]: undefined;
  [Types.FORGOT_PASSWORD]:undefined;
  [Types.NEW_PASSWORD]:undefined;
};

type ActionsType = ActionMapType<Payload>[keyof ActionMapType<Payload>];

// ----------------------------------------------------------------------

const initialState: AuthStateType = {
  isInitialized: false,
  isAuthenticated: false,
  user: null,
};

const reducer = (state: AuthStateType, action: ActionsType) => {
  if (action.type === Types.INITIAL) {
    return {
      isInitialized: true,
      isAuthenticated: action.payload.isAuthenticated,
      user: action.payload.user,
    };
  }
  if (action.type === Types.LOGIN) {
    return {
      ...state,
      isAuthenticated: true,
      user: action.payload.user,
    };
  }
  if (action.type === Types.REGISTER) {
    return {
      ...state,
      isAuthenticated: true,
      user: action.payload.user,
    };
  }
  if (action.type === Types.LOGOUT) {
    return {
      ...state,
      isAuthenticated: false,
      user: null,
    };
  }
  if (action.type === Types.FORGOT_PASSWORD) {
    return {
      ...state,
      isAuthenticated: false,
      user: null,
    };
  }
  if (action.type === Types.NEW_PASSWORD) {
    return {
      ...state,
      isAuthenticated: false,
      user: null,
    };
  }
  return state;
};

// ----------------------------------------------------------------------

export const AuthContext = createContext<JWTContextType | null>(null);

// ----------------------------------------------------------------------

type AuthProviderProps = {
  children: React.ReactNode;
};

export function AuthProvider({ children }: AuthProviderProps) {
  const [state, dispatch] = useReducer(reducer, initialState);

  const apm = useApm();

  const initialize = useCallback(async () => {
    try {
      const accessToken = typeof window !== 'undefined' ? localStorage.getItem('accessToken') : '';
      if (accessToken && isValidToken(accessToken)) {
        setSession(accessToken, {});
        // const response = await axios.get('/api/account/my-account');

        const user = jwtDecode(accessToken);

        dispatch({
          type: Types.INITIAL,
          payload: {
            isAuthenticated: true,
            user,
          },
        });
      } else {
        dispatch({
          type: Types.INITIAL,
          payload: {
            isAuthenticated: false,
            user: null,
          },
        });
      }
    } catch (error) {
      console.error(error);
      dispatch({
        type: Types.INITIAL,
        payload: {
          isAuthenticated: false,
          user: null,
        },
      });
    }
  }, []);

  useEffect(() => {
    initialize();
  }, [initialize]);

  const setAuthenticated = (accessToken : string | null | undefined) => {
    if (accessToken && isValidToken(accessToken)) {
      const user = jwtDecode(accessToken);
      setSession(accessToken,user.roles);
      dispatch({
        type: Types.INITIAL,
        payload: {
          isAuthenticated: true,
          user,
        },
      });
    }else{
      dispatch({
        type: Types.INITIAL,
        payload: {
          isAuthenticated: false,
          user: null,
        },
      });
    }
  }

  // LOGIN
  const login = async (email: string, password: string) => {
    const transaction = apm.startTransaction("Auth transaction", "component");
    const renderSpan = transaction?.startSpan("login");

    const response = await login_handler({
        code : email,
        password : password
    });

    renderSpan?.end();
    transaction?.end();
    if(response){
      if(response.data.code == 0){
        transaction?.end();
        const user = response.data.data;
        setSession(user.token, user.roles);
    
        dispatch({
          type: Types.LOGIN,
          payload: {
            user,
          },
        });
      }else{
        transaction?.end();
        throw new Error(response.data.info);
      }
    }else{
      throw new Error('Error has occurred. Please try again!');
    }
    
  };

  // REGISTER
  const register = async (email: string, password: string, firstName: string, lastName: string) => {
    const response = await axios.post('/api/account/register', {
      email,
      password,
      firstName,
      lastName,
    });
    const { accessToken, user } = response.data;

    localStorage.setItem('accessToken', accessToken);

    dispatch({
      type: Types.REGISTER,
      payload: {
        user,
      },
    });
  };

  // LOGOUT
  const logout = async () => {
    // const transaction = apm.startTransaction("Auth transaction", "component");
    // const renderSpan = transaction?.startSpan("logout");

    // const response = await logout_handler();
    
    // renderSpan?.end();
    // transaction?.end();
    // if(response.data.code == 0){
    //   transaction?.end();
    //   const user = response.data.data;
      setSession('', {});
      
      dispatch({
        type: Types.LOGOUT,
      });
    // }else{
    //   transaction?.end();
    //   throw new Error(response.data.info);
    // }
  };

  // FOGOT PASSWORD
  const forgotPassword = async (email: string) => {
    // const transaction = apm.startTransaction("Auth transaction", "component");
    // const renderSpan = transaction?.startSpan("forgotPassword");

    // const response = await forgot_password_handler({
    //     email : email
    // });
    
    // renderSpan?.end();
    // transaction?.end();
    // if(response.data.code == 0){
    //   transaction?.end();  
    //   dispatch({
    //     type: Types.FORGOT_PASSWORD,
    //   });
    // }else{
    //   transaction?.end();
    //   throw new Error(response.data.info);
    // }
  };

  const submitNewPassword = async (password: string, newPassword:string) => {
    // const transaction = apm.startTransaction("Auth transaction", "component");
    // const renderSpan = transaction?.startSpan("newPassword");

    // const response = await new_password_handler({
    //     new_pass: password,
    //     retype_pass: newPassword
    // });
    
    // renderSpan?.end();
    // transaction?.end();
    // if(response.data.code == 0){
    //   transaction?.end();  
    //   dispatch({
    //     type: Types.NEW_PASSWORD,
    //   });
    // }else{
    //   transaction?.end();
    //   throw new Error(response.data.info);
    // }
  };

  return (
    <AuthContext.Provider
      value={{
        ...state,
        method: 'jwt',
        login,
        loginWithGoogle: () => {},
        loginWithGithub: () => {},
        loginWithTwitter: () => {},
        setAuthenticated,
        logout,
        register,
        forgotPassword,
        submitNewPassword,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
}
