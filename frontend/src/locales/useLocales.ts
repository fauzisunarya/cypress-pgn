import { useTranslation } from 'react-i18next';
// components
import { useSettingsContext } from 'src/Components/settings';
//
import { allLangs, defaultLang } from './config';
import axios, { axiosInstanceUam, axiosInstanceRetail, axiosInstanceCustomer,  } from '../utils/axios';
import en from './langs/en';
import id from './langs/id';

// ----------------------------------------------------------------------

export default function useLocales() {
  const { i18n, t: translate } = useTranslation();

  const { onChangeDirectionByLang } = useSettingsContext();

  const langStorage = typeof window !== 'undefined' ? localStorage.getItem('i18nextLng') : '';

  const currentLang = allLangs.find((_lang) => _lang.value === langStorage) || defaultLang;

  const handleChangeLanguage = (newlang: string) => {
    i18n.changeLanguage(newlang);
    onChangeDirectionByLang(newlang);
    axios.defaults.headers.common['Accept-Language'] = newlang;
    axiosInstanceRetail.defaults.headers.common['Accept-Language'] = newlang;
    axiosInstanceCustomer.defaults.headers.common['Accept-Language'] = newlang;
  };

  return {
    onChangeLang: handleChangeLanguage,
    translate: (text: any, options?: any) => {
      let text_alias = text.split(' ').join('_');
      let translated = translate(text_alias.toLowerCase(), options);
      let is_success_translated = translated != text_alias.toLowerCase();
      if(!is_success_translated){
        return text;
      }
      return translated;
    },
    currentLang,
    allLangs,
  };
}

export function getMasterLocales(key:string){
  const langStorage = typeof window !== 'undefined' ? localStorage.getItem('i18nextLng') : '';
  const currentLang = allLangs.find((_lang) => _lang.value === langStorage) || defaultLang;
  let collection: any = en;
  if(currentLang.value == 'en'){
    collection = en;
  }else if(currentLang.value == 'id'){
    collection = id;
  }
  return collection[key];
}