import { ReactNode } from 'react';
import { useAuthContext } from './useAuthContext';
import LoadingScreen from '@/Components/loading-screen';

// ----------------------------------------------------------------------

type AuthGuardProps = {
  children: ReactNode;
};

export default function AuthGuard({ children }: AuthGuardProps) {
  const { isAuthenticated, isInitialized } = useAuthContext();

  if (!isInitialized || !isAuthenticated ) {
    return <LoadingScreen/>;
  }

  return <>{children}</> ;
}

