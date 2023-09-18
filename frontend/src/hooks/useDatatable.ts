import { useContext } from 'react';
import { DatatableContext } from 'src/contexts/DatatableContext';

// ----------------------------------------------------------------------

const useDatatable:any = () => useContext(DatatableContext);

export default useDatatable;
