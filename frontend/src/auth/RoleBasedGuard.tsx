import { m } from 'framer-motion';
// @mui
import { Container, Typography } from '@mui/material';
// components
import { MotionContainer, varBounce } from '../components/animate';
// assets
import { ForbiddenIllustration } from '../assets/illustrations';
//
import { Navigate } from 'react-router-dom';

// ----------------------------------------------------------------------

type RoleBasedGuardProp = {
  hasContent?: boolean;
  roles?: string[];
  children: React.ReactNode;
};

export default function RoleBasedGuard({ hasContent, roles, children }: RoleBasedGuardProp) {
  // Logic here to get current user role
  const activeRole = localStorage.getItem('activeRole');
  // const currentRole = 'user';
  const currentRole = activeRole != null && activeRole != '' ? activeRole : 'admin'; // admin;
  console.log(activeRole);
  console.log(roles);
  console.log(!roles?.includes(currentRole));
  if (typeof roles !== 'undefined' && !roles.includes(currentRole)) { 
    return hasContent ? (
      <Navigate to={'/403'} />
    ) : null;
  }

  return <>{children}</>;
}
