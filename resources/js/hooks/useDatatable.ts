import { useContext } from 'react';
import { DatatableContext } from '@/contexts/DatatableContext';

// ----------------------------------------------------------------------

const useDatatable = () => useContext(DatatableContext);

export default useDatatable;
