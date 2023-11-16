// ----------------------------------------------------------------------

function path(root: string, sublink: string) {
  return `${root}${sublink}`;
}

const ROOTS_EMPLOYEE = '/employee';
const ROOTS_ATTENDANCE = '/attendance';

// ----------------------------------------------------------------------

export const PATH_AUTH = {
  login: '/login',
};

export const PATH_EMPLOYEE = {
  root: ROOTS_EMPLOYEE 
}

export const PATH_ATTENDANCE = {
  root: ROOTS_ATTENDANCE 
}
