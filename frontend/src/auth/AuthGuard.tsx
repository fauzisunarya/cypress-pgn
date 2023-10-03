import { useState, ReactNode, useMemo, useEffect } from 'react';
import { Navigate, useLocation } from 'react-router-dom';
// components
import LoadingScreen from '../components/loading-screen';

import { useAuthContext } from './useAuthContext';
import { isValidToken } from './utils';

// ----------------------------------------------------------------------

type AuthGuardProps = {
  children: ReactNode;
};

export default function AuthGuard({ children }: AuthGuardProps) {
  const { isAuthenticated, isInitialized } = useAuthContext();
  
  const { user } = useAuthContext();
  const maintenanceMode = process.env.REACT_APP_MAINTENANCE_MODE || 'off';

  const { pathname } = useLocation();

  const [requestedLocation, setRequestedLocation] = useState<string | null>(null);

  if (!isInitialized) {
    return <LoadingScreen />;
  }

  if (!isAuthenticated) {
    if (pathname !== requestedLocation) {
      setRequestedLocation(pathname);
    }
    return <Navigate to={'/login'} />;
  }

  if(maintenanceMode === 'on' && user?.name !== 'admin'){
    return <Navigate to={'/maintenance'} />;
  }

  if (requestedLocation && pathname !== requestedLocation) {
    return <Navigate to={requestedLocation} />;
  }

  return <>{children}</>;
}
