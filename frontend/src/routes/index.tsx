import { Navigate, useRoutes } from 'react-router-dom';
import ContentList from 'src/Pages/Content/List';

// ----------------------------------------------------------------------

export default function Router() {
  return useRoutes([
    {
      path: '/',
      children: [
        { path: 'content-list', element: <ContentList /> },
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
