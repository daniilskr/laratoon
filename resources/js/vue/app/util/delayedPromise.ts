// delayedPromise это setTimeout в виде промиса (то есть setTimeout, который позволяет использовать then).
export default function delayedPromise<T>(callback: () => T, delayTimeMs: number): Promise<T> {
  return new Promise((resolve) => {
    setTimeout(() => {
      const result = callback();
      resolve(result);
    }, delayTimeMs);
  });
}
