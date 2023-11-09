import { Navigate, useRoutes } from 'react-router-dom';
// auth
import AuthGuard from '../auth/AuthGuard';
import GuestGuard from '../auth/GuestGuard';
// layouts
import CompactLayout from '../layouts/compact';
import DashboardLayout from '../layouts/dashboard';
// config
import { PATH_AFTER_LOGIN } from '../config';
//
import {
  Page404,
  ContentList
} from './elements';
import IframeExample from 'src/pages/example/IframeExample';

// ----------------------------------------------------------------------

export default function Router() {
  return useRoutes([
    {
      path: '/content',
      children: [
        { path: 'list', element: <ContentList /> },
      ],
    },
    {
      element: <CompactLayout />,
      children: [{ path: '404', element: <Page404 /> }],
    },
    {
      path: '/iframe-example',
      element: (
        <IframeExample />
      )
    },
    { path: '*', element: <Navigate to="/404" replace /> },
  ]);
}
