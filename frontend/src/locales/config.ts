// @mui
import { enUS, frFR, zhCN, viVN, arSD, idID } from '@mui/material/locale';

// PLEASE REMOVE `LOCAL STORAGE` WHEN YOU CHANGE SETTINGS.
// ----------------------------------------------------------------------

export const allLangs = [
  {
    label: 'English',
    value: 'en',
    systemValue: enUS,
    icon: '/assets/icons/flags/ic_flag_en.svg',
  },
  {
    label: 'Indonesia',
    value: 'id',
    systemValue: idID,
    icon: '/assets/icons/flags/ic_flag_id.svg',
  }
];

export const defaultLang = allLangs[0]; // English
