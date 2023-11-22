import { Navigate, useRoutes } from 'react-router-dom';
import ContentList from 'src/Pages/Content/List';

// ----------------------------------------------------------------------

export default function Router() {
  return useRoutes([
    { path: '*', element: <Navigate to="/404" replace /> },
  ]);
}
