export interface Category {
  name: string;
  subcategories: string[];
}

export const categories: Category[] = [
  {
    name: 'Brasier',
    subcategories: [
      '--- ESTILOS DE USO ---',
      'Push Up',
      'Bralette',
      'Strapless / Sin Espalda',
      'Escote Profundo',
      'Bustier',
      'Top',
      '--- SOLUCIONES Y SOPORTE ---',
      'Soporte y Reducción',
      'Postoperatorio y Maternas',
      'Control Espalda',
      'Señorero'
    ]
  },
  {
    name: 'Panty',
    subcategories: [
      '--- CORTES Y ESTILOS ---',
      'Brasilera',
      'Cachetero',
      'Bikini',
      'Boxer',
      'Señorero',
      '--- COLECCIONES ESPECIALES ---',
      'Maternidad',
      'Niña',
      '--- MULTIPACKS ---',
      'Pack'
    ]
  },
  {
    name: 'Fajas',
    subcategories: [
      '--- RECUPERACIÓN Y SALUD ---',
      'Postquirúrgica',
      'Post Parto',
      'Primera Postura',
      '--- CONTROL Y MOLDEO DIARIO ---',
      'Cinturilla',
      'Chaleco',
      'Short',
      'Camiseta',
      '--- OTRAS LÍNEAS ---',
      'Masculinas',
      'Otros'
    ]
  },
  {
    name: 'Deportivos',
    subcategories: [
      '--- MUJER - PARTES ALTAS ---',
      'Top',
      'Camiseta',
      'Chaqueta',
      '--- MUJER - PARTES BAJAS ---',
      'Short',
      'Falda',
      'Biker',
      'Legging',
      'Jogger',
      'Enterizo',
      '--- COLECCIÓN HOMBRE ---',
      'Camiseta',
      'Manga Larga',
      'Short',
      'Jogger'
    ]
  },
  {
    name: 'Trajes de Baño',
    subcategories: [
      '--- SWIMWEAR ---',
      'Bikini',
      'Malla',
      'Enterizo',
      '--- BEACHWEAR ---',
      'Pareo',
      'Camisa',
      'Pantalón',
      'Vestido'
    ]
  },
  {
    name: 'Pijamas',
    subcategories: [
      '--- NOCHE Y SEDUCCIÓN ---',
      'Baby Doll',
      'Camisón',
      'Kimono',
      '--- CONJUNTOS Y PIEZAS ---',
      'Pantalón',
      'Short',
      'Body'
    ]
  },
  {
    name: 'Hombre',
    subcategories: [
      '--- ROPA INTERIOR ---',
      'Boxer',
      'Boxer Largo',
      'Calzoncillo',
      'Boxer Deportivo',
      'Pack',
      '--- LOUNGEWEAR ---',
      'Camiseta',
      'Manga Larga',
      'Short',
      'Jogger',
      '--- SOLUCIONES ---',
      'Fajas'
    ]
  },
  {
    name: 'Accesorios',
    subcategories: [
      '--- COMPLEMENTOS DE LENCERÍA ---',
      'Broches',
      'Ligas',
      'Tablas',
      '--- MÁS ACCESORIOS ---',
      'Sombreros',
      'Otros'
    ]
  }
];

// Mapeo de nombre de categoría del menú → valor en BD
export const CATEGORY_MAP: Record<string, string> = {
  'Brasier': 'ropainterior',
  'Panty': 'ropainterior',
  'Fajas': 'fajas',
  'Deportivos': 'deportiva',
  'Trajes de Baño': 'trajes',
  'Pijamas': 'pijamas',
  'Hombre': 'mujer', // género en BD
  'Accesorios': 'accesorios',
};

// Para Brasier/Panty necesitamos filtrar por subcampo específico
export const BRASIER_MAP: Record<string, string> = {
  'Push Up': 'pushup',
  'Bralette': 'bralette',
  'Strapless / Sin Espalda': 'strapple',
  'Escote Profundo': 'escote',
  'Bustier': 'bustier',
  'Top': 'top',
  'Soporte y Reducción': 'soporte',
  'Postoperatorio y Maternas': 'postoperatorio',
  'Control Espalda': 'controlespalda',
  'Señorero': 'senyorero',
};

export const PANTY_MAP: Record<string, string> = {
  'Brasilera': 'brasilera-tanga',
  'Cachetero': 'cachetero',
  'Bikini': 'bikini',
  'Boxer': 'boxer',
  'Señorero': 'senyorero',
  'Maternidad': 'maternidad',
  'Pack': 'packs',
};
