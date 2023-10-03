// ----------------------------------------------------------------------

import { useEffect, useState } from "react";
import AuthGuard from "src/auth/AuthGuard";

export default function FormExample() {
  
  return (
    <>
    <AuthGuard>
      Hello world
    </AuthGuard>
    </>
  );
}
