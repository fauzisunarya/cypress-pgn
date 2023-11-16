import { useState, ReactNode } from 'react';
import { Navigate } from 'react-router-dom';
// components
// import LoadingScreen from '../components/loading-screen';
//
// import Login from '../pages/LoginPage';
import { useAuthContext } from './useAuthContext';
import LoadingScreen from 'src/Components/loading-screen';

// ----------------------------------------------------------------------

type AuthGuardProps = {
  children: ReactNode;
};

export default function AuthGuard({ children }: AuthGuardProps) {
  const { isAuthenticated, isInitialized } = useAuthContext();

  // const pathname = route().current();

  const [requestedLocation, setRequestedLocation] = useState<string | null>(null);

  if (!isInitialized) {
    <Navigate to={'/404'} />
    // return <LoadingScreen/>;
  }

  if (!isAuthenticated) {
    <Navigate to={'/404'} />
    // if (pathname !== requestedLocation) {
    //   setRequestedLocation(pathname ? pathname : null);
    // }
    // return <LoadingScreen/>;
  }

  // if (requestedLocation && pathname !== requestedLocation) {
  //   setRequestedLocation(null);
  //   return <Navigate to={requestedLocation} />;
  // }

  return <>{children}</> ;
}
