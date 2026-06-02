import { motion } from 'motion/react';
import { ArrowLeft } from 'lucide-react';

interface TermsAndConditionsProps {
  onBackClick: () => void;
}

export function TermsAndConditions({ onBackClick }: TermsAndConditionsProps) {
  return (
    <div className="min-h-screen bg-background">
      {/* Header */}
      <div className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="container mx-auto px-4">
          <button
            onClick={onBackClick}
            className="flex items-center gap-2 mb-6 hover:opacity-80 transition-opacity"
          >
            <ArrowLeft className="w-5 h-5" />
            <span>Volver</span>
          </button>
          <h1 className="text-4xl md:text-5xl">Condiciones Generales</h1>
        </div>
      </div>

      {/* Content */}
      <div className="container mx-auto px-4 py-12 md:py-16">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="max-w-4xl mx-auto space-y-8"
        >
          {/* Introduction */}
          <section className="prose prose-lg max-w-none">
            <p className="text-muted-foreground leading-relaxed">
              La siguiente información aplica para todas las personas que visiten el sitio Web de www.regalelenceria.com. El uso de este Sitio Web implica que usted está de acuerdo con nuestros Términos de Privacidad y nuestras Condiciones de Uso. Si usted no está de acuerdo con estos términos y condiciones, no utilice el sitio. Regale Lencería, pone a su disposición este Servicio Online donde podrá comprar de forma rápida y segura todos nuestros productos de lencería y ropa interior. Además disfrutar, promociones, regalos y ofertas especiales.
            </p>
            <p className="text-muted-foreground leading-relaxed">
              Por favor lea detenidamente las presentes condiciones antes de realizar cualquier compra. La realización de un pedido y su confirmación por parte del Usuario, implica la aceptación plena de los presentes términos y condiciones de venta. Al realizar una compra en nuestra web, usted nos garantiza que es mayor de 18 años y que tiene capacidad legal necesaria para contratar. Nos reservamos el derecho de actualizar o cambiar los términos y condiciones del sitio, es recomendable que pueda leer esta sección cada vez que utilice este sitio.
            </p>
          </section>

          {/* Características principales */}
          <section>
            <h2 className="text-2xl md:text-3xl mb-4">Características principales de nuestros productos y / o servicios</h2>
            <p className="text-muted-foreground leading-relaxed">
              Las descripciones de los Productos y Servicios ofrecidos en el Sitio Web se realizan en base a la información y documentación proporcionada por los fabricantes y proveedores de Regale Lencería.
            </p>
          </section>

          {/* Marcas */}
          <section>
            <h2 className="text-2xl md:text-3xl mb-4">Marcas</h2>
            <p className="text-muted-foreground leading-relaxed">
              Regale Lencería a través de las Marcas que comercializa en www.regalelenceria.com se reservan todos los derechos que conciernen a sus marcas y nombres. Estas marcas, nombres, logos y/o imágenes asociadas, están registradas y protegidas por las leyes y tratados internacionales.
            </p>
          </section>

          {/* Colores */}
          <section>
            <h2 className="text-2xl md:text-3xl mb-4">Colores</h2>
            <p className="text-muted-foreground leading-relaxed">
              Regale Lencería ha realizado un esfuerzo para mostrar los colores de las prendas de la forma más cercana a la realidad posible. No obstante, ello, el color de las prendas que aparecen en pantalla puede estar sujeto a variaciones dependiendo de la calidad del monitor de tu computador. En este sentido Regale Lencería no puede garantizar que los colores que aparezcan en tu monitor se ajusten fielmente a la realidad.
            </p>
          </section>

          {/* Proceso de compras */}
          <section>
            <h2 className="text-2xl md:text-3xl mb-4">Proceso de compras y requisitos para realizar pedidos/compras</h2>
            <p className="text-muted-foreground leading-relaxed mb-4">
              Podrá navegar en el sitio web hasta encontrar el artículo deseado. Una vez localizado, pulsando sobre el mismo accederá a toda la información de detalle que le ofrece el sitio web. Desde aquí podrá añadir el artículo a su bolsa de compras.
            </p>
            <p className="text-muted-foreground leading-relaxed mb-4">
              Si está realizando un pedido por primera vez con nosotros, se le pedirá que se registre introduciendo su dirección de correo electrónico y una contraseña con la que podrá crear y acceder a su cuenta. En caso de que se trate de un usuario registrado y con una cuenta activa, deberá introducir su e-mail y contraseña y el sistema reconocerá sus datos.
            </p>
            <p className="text-muted-foreground leading-relaxed mb-4">
              Una vez esté en su cuenta podrá continuar su compra y finalmente confirmar el pedido donde se le indicará el precio total de la misma, incluyendo IVA y costos de envío.
            </p>
            <p className="text-muted-foreground leading-relaxed">
              En el plazo máximo de 48 horas desde la recepción del pedido, se procederá al envío del mismo El proceso genera un comprobante de pago correspondiente que será enviado al correo electrónico.
            </p>
          </section>

          {/* Como realizar una compra */}
          <section>
            <h3 className="text-xl md:text-2xl mb-4">Como realizar una compra:</h3>
            <ul className="space-y-2 text-muted-foreground">
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Ser mayor de 18 años</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Localizar los productos que el Usuario desea adquirir.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Elegir los colores, tallas y cantidades que el Usuario desea.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Añadir los productos seleccionados a la bolsa de compras del sitio Web.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Seleccionar la modalidad de envío de los productos que se desea adquirir.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Identificarse o crear una nueva cuenta propia e intransferible de cada Usuario.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Revisar todos los elementos de la compra seleccionada.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Seleccionar la modalidad de pago de la compra.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Proceder al pago.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>El Usuario recibirá un correo electrónico con todo el detalle de la compra</span>
              </li>
            </ul>
          </section>

          {/* Disponibilidad */}
          <section>
            <h2 className="text-2xl md:text-3xl mb-4">Disponibilidad y sustituciones</h2>
            <p className="text-muted-foreground leading-relaxed">
              Incluimos en el sitio web información sobre la disponibilidad de los productos que vendemos y esta se detalla en la página de información de cada uno de los productos. Cuando procesamos su pedido, le informaremos lo antes posible por correo electrónico si cualquiera de los productos incluidos en su pedido no fuera disponible.
            </p>
          </section>

          {/* Precios */}
          <section>
            <h2 className="text-2xl md:text-3xl mb-4">Precios</h2>
            <p className="text-muted-foreground leading-relaxed mb-4">
              El precio de cada producto está debidamente indicado en la página de información de cada uno de los productos, así como su descripción correspondiente.
            </p>
            <p className="text-muted-foreground leading-relaxed mb-4">
              De acuerdo con la legislación vigente, toda compra realizada desde nuestro sitio web incluye impuestos de Ley. Todos los precios se muestran en dólares ($) y deben ser considerados como el precio final a pagar por el cliente (incluyendo el IVA). Todos los pagos pueden ser efectuados en Bolivianos con cualquiera de nuestros métodos de pago.
            </p>
            <p className="text-muted-foreground leading-relaxed">
              Asimismo, estos precios incluyen los gastos de envío de los Productos hasta el 5to Anillo de la ciudad de Santa Cruz-Bolivia. Sin embargo, los precios relacionados a gastos de envío se detallan en el proceso de pago y deben ser aceptados por el Usuario en el momento de formalizar el pedido. El precio del pedido se calculará automáticamente en el momento de ir llenando la bolsa de compras. Los precios pueden cambiar en cualquier momento. Sin embargo, los posibles cambios no afectarán a los Productos de los que Regale Lencería ya haya enviado la Confirmación de Envío.
            </p>
          </section>

          {/* Gastos de envío */}
          <section>
            <h2 className="text-2xl md:text-3xl mb-4">Gastos de envío</h2>
            <p className="text-muted-foreground leading-relaxed mb-4">
              Los gastos de envío se calculan automáticamente durante el proceso de compra. Los gastos de envío aplicables son las que se detallan a continuación:
            </p>
            <ul className="space-y-2 text-muted-foreground">
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Entrega hasta 5to Anillo sin costo, por compra mínima de 30$.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Entrega del 5to al 8vo o fuera de este, serán de 5,00 $, y/o se calcularán automáticamente en el momento de la compra.</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-primary mt-1">•</span>
                <span>Desde el 09/04/2018 hasta nuevos cambios los pedidos incluyen los gastos de envío hasta el 5to Anillo, por compra mínima de 30$.</span>
              </li>
            </ul>
          </section>

          {/* Medios de pago */}
          <section>
            <h2 className="text-2xl md:text-3xl mb-4">Medios de pago admitidos</h2>
            <p className="text-muted-foreground leading-relaxed mb-4">
              El pago del producto adquirido podrá realizarse:
            </p>
            <p className="text-muted-foreground leading-relaxed mb-4">
              Mediante Tarjeta de crédito (Visa o MasterCard) o Tarjeta de débito.
            </p>
            <p className="text-muted-foreground leading-relaxed mb-4">
              El importe del pedido se cargará en la tarjeta, por lo que deberá indicar el número, la fecha de caducidad, así como el código de seguridad CVV (incluido en el anverso). La procesadora de Pagos autentica y garantiza la seguridad de los pagos.
            </p>
            <p className="text-muted-foreground leading-relaxed">
              Estos datos no serán guardados o manipulados por nosotros, sino que serán registradas directamente en la procesadora de pagos de la entidad financiera correspondiente. El cargo se llevará a cabo en tiempo real a través de esta pasarela de pago. En caso de que el cargo no pueda llevarse a cabo finalmente (por cualquier causa), el pedido quedará anulado automáticamente.
            </p>
          </section>

          {/* Back button */}
          <div className="pt-8 border-t border-border">
            <button
              onClick={onBackClick}
              className="flex items-center gap-2 text-primary hover:opacity-80 transition-opacity"
            >
              <ArrowLeft className="w-5 h-5" />
              <span>Volver al inicio</span>
            </button>
          </div>
        </motion.div>
      </div>
    </div>
  );
}
