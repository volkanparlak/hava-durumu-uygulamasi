![Hava Durum Uygulamasi](https://i.hizliresim.com/6bhpqll.png)

Bu proje, kullanıcıların bir şehir ismi girerek o şehrin güncel hava durumunu görüntülemelerine olanak tanıyan hava durumu uygulamasıdır.

## Özellikler

- Şehir ismine göre hava durumu sorgulama
- Sıcaklık, nem oranı, rüzgar hızı ve hava durumu gibi bilgileri gösterme
- Responsive tasarım

## Kullanılan Teknolojiler

- PHP: Sunucu tarafı kodlama ve API işlemleri için
- HTML5 & CSS3: Kullanıcı arayüzü için
- JavaScript: Dinamik sayfa işlemleri için (Gerekiyorsa)
- cURL: Web içeriği çekmek için
- DOMDocument ve DOMXPath: HTML içeriğini işlemek için

## Kullanım

- `index.php`: Kullanıcıların hava durumu sorgulaması yapması için ana sayfa.
- `apihava.php`: Hava durumu API'sinden veri çekmek için kullanılan arka uç dosyası.
- `/images`: Hava durumu simgelerinin saklandığı klasör.

Hava durum API bilgisi TRTHaber üzerinden çekilmiştir.
