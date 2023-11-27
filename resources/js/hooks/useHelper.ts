import { useContext } from 'react';
import { HelperContext } from '@/contexts/HelperContext';

// ----------------------------------------------------------------------

const useHelper = () => useContext(HelperContext);

export default useHelper;
