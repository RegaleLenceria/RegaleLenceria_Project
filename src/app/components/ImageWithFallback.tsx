import { useState } from 'react';

interface ImageWithFallbackProps extends React.ImgHTMLAttributes<HTMLImageElement> {
  src: string;
  alt: string;
  fallback?: string;
}

export function ImageWithFallback({ src, alt, fallback, className, ...props }: ImageWithFallbackProps) {
  const [error, setError] = useState(false);
  const [loading, setLoading] = useState(true);

  const handleError = () => {
    setError(true);
    setLoading(false);
  };

  const handleLoad = () => {
    setLoading(false);
  };

  if (error) {
    return (
      <div className={`bg-gradient-to-br from-muted to-muted/50 flex items-center justify-center ${className}`}>
        <span className="text-muted-foreground text-sm">{fallback || alt}</span>
      </div>
    );
  }

  return (
    <div className="relative w-full h-full">
      {loading && (
        <div className="absolute inset-0 bg-gradient-to-br from-muted to-muted/50 animate-pulse" />
      )}
      <img
        src={src}
        alt={alt}
        className={`${className} ${loading ? 'opacity-0' : 'opacity-100 transition-opacity duration-500'}`}
        onError={handleError}
        onLoad={handleLoad}
        loading="lazy"
        {...props}
      />
    </div>
  );
}
