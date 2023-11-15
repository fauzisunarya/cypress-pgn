import { Suspense, lazy, ElementType } from 'react';

const Loadable = (Component: ElementType) => (props: any) =>
  (
    <Suspense >
      <Component {...props} />
    </Suspense>
  );

// export const Attendance = Loadable(lazy(() => import('../pages/attendance/index')));