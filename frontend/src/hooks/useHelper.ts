import { useContext } from 'react';
import { HelperContext } from 'src/contexts/HelperContext';

// ----------------------------------------------------------------------

const useHelper = () => useContext(HelperContext);

export default useHelper;
